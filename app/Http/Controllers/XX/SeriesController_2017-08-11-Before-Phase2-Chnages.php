<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontpostloginbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Series;
use App\Seriesmedia;
use App\Seriesmedialikedislike;
use App\Seriesmediacomment;
use App\Emailtemplate;

class SeriesController extends FrontpostloginbaseController {
    
    public function add() {
        $data = [];
        $data['start_series'] = true;
        $data['series'] = new Series();
        $data['seriesmediacounts'] = 0;
        return view('seriesadd', $data);
    }

    public function edit($slug) {
        $data = [];
        $series = Series::ofuser($this->globaldata['user']->userid)->where('slug', $slug)->get();
        if(!$series->isEmpty()) {
            $series = $series->first();
            $data['series'] = $series;
            $data['seriesmediacounts'] = $series->seriesmedias()->get()->count();
            return view('seriesadd', $data);
        }
        else {
            return view('errors.404');
        }
    }

    public function submit(Request $request) {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];

            $seriesid = intval($request->seriesid);
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'title'             => 'required',
                //'description'       => 'required'
            ];

            /*if($seriesid == 0) {
                $rules['file'] = 'required|mimes:jpg,jpeg,png';
            }
            else {
                $rules['file'] = 'mimes:jpg,jpeg,png';
            }*/

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {
                
                $creatorid = intval($this->globaldata['user']->userid);
                $series = ($seriesid == 0) ? new Series() : Series::ofuser($creatorid)->find($seriesid);
                
                if(!empty($series)) {

                    $title = trim($request->title);
                    if($seriesid == 0) {
                        $slug = $this->checkSlug(str_slug($title));
                        $series->slug               = $slug;
                        $series->isongoing          = 0;
                        $series->status             = 1;
                        $series->userid             = $creatorid;
                    }
                
                    $series->title              = $title;
                    $series->description        = trim($request->description);
                    
                    $result = $series->save();
                    $captionerror = 'Unable to save your data at this moment. Kindly try again.';
                    
                    // SERIES INSERT SUCCESS
                    if($result) {

                        // SERIES IMAGE UPLOADED
                        $imageuploaded = $this->uploadSeriesImage($series, $request);

                        if($imageuploaded) {

                            $allok = true;

                            // SERIES MEDIAS INSERT/UPDATE/DELETE
                            $seriesmediaids                 = Input::get('seriesmediaid');
                            $seriesmediadeleted             = Input::get('seriesmediadeleted');
                            $seriesmediaimmidiatepublish    = Input::get('seriesmediaimmidiatepublish');
                            $seriesmediavideotypes          = Input::get('seriesmediavideotype');
                            $seriesmediahasvideos           = Input::get('seriesmediahasvideo');
                            $seriesmediavideoindexs         = Input::get('seriesmediavideoindex');
                            $seriesmediahasvideothumbs      = Input::get('seriesmediahasvideothumb');
                            $seriesmediavideothumbindexs    = Input::get('seriesmediavideothumbindex');

                            $seriesmediatitles              = empty(Input::get('seriesmediatitle')) ? [] : Input::get('seriesmediatitle');
                            $seriesmediadescriptions        = Input::get('seriesmediadescription');
                            $seriespublishdates             = Input::get('seriespublishdate');
                            $seriesmediavideofiles          = $request->file('seriesmediavideofile');
                            $seriesmediavideourls           = Input::get('seriesmediavideourl');
                            $seriesmediavideothumbfiles     = $request->file('seriesmediavideothumbfile');

                            $newmediauploaded = false;
                            
                            // LOOP ALL SERIES MEDIAS
                            foreach($seriesmediatitles as $index => $seriesmediatitle) {
                                
                                $seriesmediaid = $seriesmediaids[$index];

                                // IF SERIES MEDIA IS NOT DELETED
                                if($seriesmediadeleted[$index] == 0) {

                                    $seriesmedia = ($seriesmediaid == 0) ? new Seriesmedia() : Seriesmedia::find($seriesmediaid);

                                    // IF SERIES MEDIA IS VALID
                                    if(!empty($seriesmedia)) {

                                        // IF EDIT CASE AND NEW VIDEO TYPE SELECTED IS URL
                                        if($seriesmediavideotypes[$index] == 0 && $seriesmediaid > 0) {
                                            if($seriesmedia->hasfile) {
                                                $filespath = public_path($seriesmedia->filesdir);
                                                File::deleteDirectory($filespath);
                                            }
                                        }

                                        // UPDATE SERIES MEDIAS NEW VALUE
                                        $seriesmedia->title = $seriesmediatitle;
                                        $seriesmedia->description = $seriesmediadescriptions[$index];
                                        $seriesmedia->isfile = $seriesmediavideotypes[$index];
                                        $seriesmedia->fileurl = ($seriesmediavideotypes[$index] == 1) ? '' : $seriesmediavideourls[$index];
                                        $seriesmedia->status = 1;
                                        $seriesmedia->immidiatepublish = intval($seriesmediaimmidiatepublish[$index]);
                                        if($seriesmedia->immidiatepublish == 1) {
                                            $seriesmedia->publishdate = null;
                                        }
                                        else {
                                            $seriesmedia->publishdate = $seriespublishdates[$index];
                                        }
                                        
                                        // SERIES MEDIA INSERT/UPDATE SUCCESS
                                        if($series->seriesmedias()->save($seriesmedia)) {

                                            // UPLOAD VIDEO FILE IF SELECTED
                                            if ($seriesmediahasvideos[$index] == 1) {

                                                $filepath = public_path($seriesmedia->filedir);
                                                $videoindex = $seriesmediavideoindexs[$index];
                                                $file = $seriesmediavideofiles[$videoindex];
                                                $extension = $file->getClientOriginalExtension();

                                                // DELETE OLD VIDEO FILE
                                                File::deleteDirectory($filepath);
                                                File::makeDirectory($filepath, 0777, true, true);
                                                $fileName = getTempName($filepath, $extension);

                                                // VIDEO FILE UPLOAD SUCCESS
                                                if($file->move($filepath, $fileName)) {
                                                    
                                                    $seriesmedia->filename = $fileName;
                                                    
                                                    // VIDEO FILE NAME UPDATE IN SERIESMEDIA TABLE FAIL
                                                    if(!$seriesmedia->update()) {

                                                        $allok = false;

                                                    }

                                                }
                                                // VIDEO FILE UPLOAD FAIL
                                                else {

                                                    $allok = false;

                                                }

                                            }

                                            // UPLOAD VIDEO THUMBNAIL FILE IF SELECTED
                                            if ($seriesmediahasvideothumbs[$index] == 1) {

                                                $thumbpath = public_path($seriesmedia->thumbdir);
                                                $thumbindex = $seriesmediavideothumbindexs[$index];
                                                $thumb = $seriesmediavideothumbfiles[$thumbindex];
                                                $extension = $thumb->getClientOriginalExtension();

                                                // DELETE OLD THUMB FILE
                                                File::deleteDirectory($thumbpath);
                                                File::makeDirectory($thumbpath, 0777, true, true);
                                                $thumbName = getTempName($thumbpath, $extension);

                                                // VIDEO THUMB FILE UPLOAD SUCCESS
                                                if($thumb->move($thumbpath, $thumbName)) {
                                                    
                                                    $seriesmedia->filethumbname = $thumbName;
                                                    
                                                    // VIDEO THUMB FILE NAME UPDATE IN SERIESMEDIA TABLE FAIL
                                                    if(!$seriesmedia->update()) {

                                                        $allok = false;

                                                    }

                                                }
                                                // VIDEO THUMB FILE UPLOAD FAIL
                                                else {

                                                    $allok = false;

                                                }

                                            }

                                        }
                                        // SERIES MEDIA INSERT/UPDATE FAIL
                                        else {

                                            $allok = false;

                                        }                                       

                                    }

                                    if($allok) {
                                        if($seriesmediaid == 0) {
                                            $newmediauploaded = true;
                                        }
                                    }

                                }
                                // IF SERIES MEDIA IS DELETED
                                else {

                                    // IF SERIES MEDIA TO BE DELETED IS NOT NEW ONE 
                                    if($seriesmediaid > 0) {

                                        $seriesmedia = Seriesmedia::find($seriesmediaid);

                                        // IF SERIES MEDIA TO BE DELETED EXISTS
                                        if(!empty($seriesmedia)) {

                                            $alldeleted = true;

                                            // DELETE FROM SERIESMEDIACOMMENTS TABLE
                                            if(!$seriesmedia->seriesmediacomments()->get()->isEmpty()) {
                                                $alldeleted = Seriesmediacomment::destroy(array_column($seriesmedia->seriesmediacomments()->get()->toArray(), 'seriesmediacommentid'));
                                            }

                                            if($alldeleted) {

                                                // DELETE FROM SERIESMEDIALIKESDISLIKES TABLE
                                                if(!$seriesmedia->seriesmedialikesdislikes()->get()->isEmpty()) {
                                                    $alldeleted = Seriesmedialikedislike::destroy(array_column($seriesmedia->seriesmedialikesdislikes()->get()->toArray(), 'seriesmedialikesdislikeid'));
                                                }

                                                if($alldeleted) {

                                                    // DELETE SERIES MEDIA FILES IF ANY
                                                    if($seriesmedia->hasfile) {
                                                        $alldeleted = File::deleteDirectory(public_path($seriesmedia->filesdir));   
                                                    }

                                                    if($alldeleted) {

                                                        // DELETE SERIES MEDIA
                                                        if(!$seriesmedia->delete()) {
                                                            $allok = false;
                                                        }    

                                                    }
                                                    else {
                                                        $allok = false;
                                                    }

                                                }
                                                else {
                                                    $allok = false;
                                                }

                                            }
                                            else {
                                                $allok = false;
                                            }

                                        }

                                    }

                                }

                            }

                            if($allok) {

                                // NOTIFY FOLLOWERS (IF ANY) ABOUT NEW MEDIA UPLOADED
                                if($newmediauploaded) {
                                    $followusers = $this->globaldata['user']->followers()->get();
                                    $this->notifyFollowersForNewMedia($series, $followusers);
                                }

                                $data['type'] = 'success';
                                $data['caption'] = 'Series details saved successfully.';
                                $data['redirectUrl'] = url('/series/my');

                            }
                            else {

                                $data['type'] = 'error';
                                $data['caption'] = 'Some of the data not saved. Please try again.';

                            }

                        }
                        else {

                            $data['type'] = 'error';
                            $data['caption'] = $captionerror;

                        }

                    }
                    // SERIES INSERT FAIL
                    else {

                        $data['type'] = 'error';
                        $data['caption'] = $captionerror;

                    }

                }
                // INVALID SERIES
                else {

                    $data['type'] = 'error';
                    $data['caption'] = 'Invalid series.';

                }

            }
            // VALIDATION FAIL
            else {

                $errors = $validator->errors()->all();
                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    private function notifyFollowersForNewMedia($series, $followusers) {
        
        $sendmailresult = true;

        if(!$followusers->isEmpty() && !empty($series)) {

            $user = $this->globaldata['user'];
            $emailtemplateid = config('app.constants.emailtemplates.notifyfollowersfornewmedia');    
            $serieslink = url('series/'.$series->slug);

            foreach($followusers as $followuser) {

                $follower = $followuser->follower()->active()->get();
                $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();

                if(!$emailtemplate->isEmpty() && !$follower->isEmpty()) {

                    $follower = $follower->first();
                    $emailtemplate = $emailtemplate->first();

                    // SET ARRAY OF REPLACE STRINGS AND ACTUAL VALUES
                    $core_array = [
                        '#follower_email#', 
                        '#follower_name#', 
                        '#follower_fname#', 
                        '#user_email#', 
                        '#user_name#',
                        '#user_fname#',
                        '#series_name#', 
                        '#series_link#'
                    ];
                    $dynamic_array = [
                        $follower->email,
                        ucfirst($follower->fullname),
                        ucfirst($follower->firstname),
                        $user->email,
                        ucfirst($user->fullname),
                        ucfirst($user->firstname),
                        ucfirst($series->title),
                        $serieslink
                    ];

                    // REPLACE DYNAMIC DATA IN EMAIL TEMPLATE
                    $emailtemplate->replaceDynamicData($core_array, $dynamic_array);

                    // SEND MAIL
                    Mail::send([], [], function($message) use ($emailtemplate) {
                        $message->to($emailtemplate->mailto, $emailtemplate->mailtoname);
                        $message->from($emailtemplate->mailfrom, $emailtemplate->mailfromname);
                        $message->subject($emailtemplate->mailsubject);
                        $message->setBody($emailtemplate->mailbody, 'text/html');
                        if(!empty($emailtemplate->replyto)) {
                            $message->replyTo($emailtemplate->replyto, $emailtemplate->replytoname);
                        }
                        if(!empty($emailtemplate->ccto)) {
                            $message->cc($emailtemplate->ccto, $emailtemplate->cctoname);
                        }
                        if(!empty($emailtemplate->bccto)) {
                            $message->bcc($emailtemplate->bccto, $emailtemplate->bcctoname);
                        }
                    });
                            
                    // MAIL SENDING FAIL
                    if(count(Mail::failures()) > 0) {
                        $sendmailresult = false;
                    }

                }
            }
        }

        return $sendmailresult;
    }

    private function uploadSeriesImage($series, $request) {
        $result = false;
        $filepath = public_path($series->filedir);

        // UPLOAD IMAGE FILE IF EXIST
        if ($request->hasFile('file')) {
            $file = $request->file('file');            
            if($file->isValid()) {

                // DELETE OLD FILE
                File::deleteDirectory($filepath);
                
                $extension = $file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->fit(config('app.constants.series_image_width'), config('app.constants.series_image_height'), function ($constraint) {
                    $constraint->upsize();
                });
                $filecreated = File::makeDirectory($filepath, 0777, true, true);

                if($filecreated) {
                    $fileName = getTempName($filepath, $extension);
                    if($img->save($filepath . $fileName)) {
                        $series->filename = $fileName;
                        if($series->update()) {
                            $result = true;
                        }
                    }
                }
            }
        }
        else {
            $result = true;
        }
        return $result;
    }

    // Check slug and retun unique slug
    public function checkSlug($slug){
        $res  = Series::Where('slug', $slug)->latest()->first();
        if(count($res) > 0) { 
            $slug_array = explode('-', $res->slug);
            $last = (int)$slug_array[count($slug_array) - 1];
            if($last > 0) {
                array_pop($slug_array);
                $count = $last;
            }
            else {
                $count = 0;
            }
            $count++;
            $new_slug = implode('-',$slug_array).'-'.$count;
            $res = Series::Where('slug', $new_slug)->latest()->first();
            if(count($res) > 0) {
                $new_slug = $this->checkSlug($new_slug);
            }
            return $new_slug;
        }
        else {
            return $slug;
        }
    }

    public function destroy(Request $request) {
        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesid' => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {
                
                $loggedinuserid = intval($this->globaldata['user']->userid);
                $seriesid = intval($request->seriesid);
                $series = Series::active()->ofuser($loggedinuserid)->find($seriesid);

                // SERIES EXIST
                if(!empty($series)) {

                    $alldeleted = true;
                    $data['type'] = 'error';
                    $data['caption'] = 'Unable to delete your series at this moment. Kindly try after some time.';

                    // DELETE FROM SERIESMEDIACOMMENTS TABLE
                    if(!$series->seriesmediacomments()->get()->isEmpty()) {
                        $alldeleted = Seriesmediacomment::destroy(array_column($series->seriesmediacomments()->get()->toArray(), 'seriesmediacommentid'));
                    }

                    if($alldeleted) {

                        // DELETE FROM SERIESMEDIALIKESDISLIKES TABLE
                        if(!$series->seriesmedialikesdislikes()->get()->isEmpty()) {
                            $alldeleted = Seriesmedialikedislike::destroy(array_column($series->seriesmedialikesdislikes()->get()->toArray(), 'seriesmedialikesdislikeid'));
                        }

                        if($alldeleted) {

                            // DELETE FROM SERIESMEDIAS TABLE
                            if(!$series->seriesmedias()->get()->isEmpty()) {
                                $alldeleted = Seriesmedia::destroy(array_column($series->seriesmedias()->get()->toArray(), 'seriesmediaid'));
                            }

                            if($alldeleted) {

                                // DELETE PHYSICAL FILES OF SERIES
                                $seriesdir = public_path($series->seriesdir);
                                if(File::exists($seriesdir)) {
                                    if(!File::deleteDirectory($seriesdir)) {
                                        $alldeleted = false;
                                    }
                                }

                                if($alldeleted) {

                                    // DELETE THE MAIN SERIES FROM SERIES TABLE
                                    if($series->delete()) {

                                        $data['type'] = 'success';
                                        $data['caption'] = 'Series deleted successfully.';
                                        $data['redirectUrl'] = url('/series/my');

                                    }

                                }

                            }

                        }

                    }
                    
                }
                // SERIES DOES NOT EXIST
                else {

                    $data['type'] = 'error';
                    $data['caption'] = 'Invalid series.';

                }

            }
            // VALIDATION FAIL
            else {

                $errors = $validator->errors()->all();
                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function addcomment(Request $request) {

        $data = [];
        $data['type'] = 'error';
        
        // MAKE VALIDATION RULES FOR RECEIVED DATA
        $rules = [
            'seriesmediaid'   => 'required',
            'comment'         => 'required'
        ];

        // VALIDATE RECEIVED DATA
        $validator = Validator::make($request->all(), $rules);

        // VALIDATION SUCCESS
        if(!$validator->fails()) {

            $seriesmediaid = intval($request->seriesmediaid);
            $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

            if(!empty($seriesmedia)) {

                $commentorid = $this->globaldata['user']->userid;
                $comment = trim($request->comment);
                
                $seriesmediacomment = new Seriesmediacomment();
                $seriesmediacomment->comment = $comment;
                $seriesmediacomment->userid = $commentorid;
                $seriesmediacomment->status = 1;

                if($seriesmedia->seriesmediacomments()->save($seriesmediacomment)) {
                    $data['type'] = 'success';
                    $data['newcomment'] = view('seriesmediacomment', ['seriesmediacomment' => $seriesmediacomment])->render();
                }
                else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Unable to add your comment at this moment. Please try after some time.';
                }


            }
            else {
                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';
            }

        }
        // VALIDATION FAIL
        else {

            $errors = $validator->errors()->all();
            $data['type'] = 'error';
            $data['caption'] = 'One or more invalid input found.';
            $data['errorfields'] = $validator->errors()->keys();

        }

        return response()->json($data);

    }

    public function getcomments(Request $request) {
        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $data['type'] = 'error';
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid'   => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $seriesmediaid = intval($request->seriesmediaid);
                $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

                if(!empty($seriesmedia)) {

                    if($seriesmedia->series->userid == $this->globaldata['user']->userid) {
                        $seriesmediacomments = $seriesmedia->seriesmediacomments()->get();
                    }
                    else {
                        $seriesmediacomments = Seriesmediacomment::where('seriesmediacommentid', 0)->get();
                    }

                    $data['type'] = 'success';
                    $data['html'] = view('ajax.seriesmediacomments', ['seriesmediacomments' => $seriesmediacomments])->render();
                    $data['title'] = $seriesmedia->title;

                }
                else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Invalid series media.';
                }

            }
            // VALIDATION FAIL
            else {

                $errors = $validator->errors()->all();
                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function deletecomment(Request $request) {

        $data = [];
        $data['type'] = 'error';
        
        // MAKE VALIDATION RULES FOR RECEIVED DATA
        $rules = [
            'seriesmediacommentid'   => 'required'
        ];

        // VALIDATE RECEIVED DATA
        $validator = Validator::make($request->all(), $rules);

        // VALIDATION SUCCESS
        if(!$validator->fails()) {

            $seriesmediacommentid = intval($request->seriesmediacommentid);
            $seriesmediacomment = Seriesmediacomment::active()->find($seriesmediacommentid);

            if(!empty($seriesmediacomment)) {

                if($seriesmediacomment->seriesmedia->series->userid == $this->globaldata['user']->userid) {

                    if($seriesmediacomment->delete()) {

                        $data['type'] = 'success';

                    }
                    else {
                        $data['type'] = 'error';
                        $data['caption'] = 'Unable to delete the comment at this moment. Please try again.';
                    }

                }
                else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Invalid comment.';
                }

            }
            else {
                $data['type'] = 'error';
                $data['caption'] = 'Invalid comment.';
            }

        }
        // VALIDATION FAIL
        else {

            $errors = $validator->errors()->all();
            $data['type'] = 'error';
            $data['caption'] = 'One or more invalid input found.';
            $data['errorfields'] = $validator->errors()->keys();

        }

        return response()->json($data);

    }

    public function like(Request $request) {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $data['type'] = 'error';
            $data['count'] = 0;
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid'   => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $seriesmediaid = intval($request->seriesmediaid);
                $likevalue = intval($request->likevalue);
                $userid = $this->globaldata['user']->userid;
                $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

                if(!empty($seriesmedia)) {

                    // IF LIKE
                    if($likevalue == 1) {

                        $liked = Seriesmedialikedislike::ofUser($userid)->likes()->where('seriesmediaid', $seriesmediaid)->get();
                        if($liked->isEmpty()) {
                            $seriesmedialikedislike = new Seriesmedialikedislike();
                            $seriesmedialikedislike->seriesmediaid = $seriesmediaid;
                            $seriesmedialikedislike->userid = $userid;
                            $seriesmedialikedislike->type = 1;
                            if($seriesmedialikedislike->save()) {
                                $data['type'] = 'success';
                            }
                        }
                        else {
                            $data['type'] = 'success';
                        }

                    }
                    // IF UNLIKE
                    else {
                        $liked = Seriesmedialikedislike::ofUser($userid)->likes()->where('seriesmediaid', $seriesmediaid)->get();
                        if($liked->isEmpty()) {
                            $data['type'] = 'success';
                        }
                        else {
                            $seriesmedialikedislike = $liked->first();
                            if($seriesmedialikedislike->delete()) {
                                $data['type'] = 'success';
                            }
                        }
                    }

                    if($data['type'] == 'success') {
                        $count = Seriesmedialikedislike::likes()->where('seriesmediaid', $seriesmediaid)->count();
                        $data['count'] = $count;
                    }

                }

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }
}