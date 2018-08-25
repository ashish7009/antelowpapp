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
use App\Followuser;
use App\Notification;
use App\Mediaclick;
use Carbon\Carbon;
use \Datetime;

class SeriesController extends FrontpostloginbaseController {
    
    /*public function add() {
        $data = [];
        $data['start_series'] = true;
        $data['series'] = new Series();
        $data['seriesmediacounts'] = 0;
        return view('seriesadd', $data);
    }*/

    /*public function edit($slug) {
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
    }*/

   
    public function addnew(Request $request) {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $loggedinuserid = $this->globaldata['user']->userid;
            $series = Series::ofuser($loggedinuserid)->get();

            if(!$series->isEmpty()) {

                $series = $series->first();

                // SERIES MEDIAS INSERT/UPDATE/DELETE
                $seriesmediavideofile           = $request->file('seriesmediavideofile');
                $seriesmediatitle               = $request->seriesmediatitle;
                $seriesmediaimmidiatepublish    = $request->seriesmediaimmidiatepublish;
                $seriesmediadate                = intval($request->seriesmediadate);
                $seriesmediamonth               = intval($request->seriesmediamonth);
                $seriesmediahour                = intval($request->seriesmediahour);
                $seriesmediaminute              = intval($request->seriesmediaminute);

                $newmediauploaded = false;
                $allok = true;
                $newseriesmedias = [];

                $seriesmedia = new Seriesmedia();
                $seriesmedia->title = $seriesmediatitle;
                $seriesmedia->isfile = 1;
                $seriesmedia->status = 1;
                $seriesmedia->immidiatepublish = $seriesmediaimmidiatepublish;
                if($seriesmedia->immidiatepublish == 0) {
                    $seriesmedia->publishdate = date('Y') . '-' . $seriesmediamonth . '-' . $seriesmediadate . ' ' . $seriesmediahour . ':' . $seriesmediaminute . ':00';
                }

                // SERIES MEDIA INSERT/UPDATE SUCCESS
                if($series->seriesmedias()->save($seriesmedia)) {

                    $newseriesmedias[] = $seriesmedia;
                    $file = $seriesmediavideofile;
                    $seriesmediaid = 0;
                    $uploadresponse = $this->uploadVideo($seriesmedia, $file, $seriesmediaid);

                    if(!$uploadresponse['result']) {
                        $allok = false;
                        $captionerror = $uploadresponse['message'];
                    }

                }

                if($allok) {

                    // NOTIFY FOLLOWERS (IF ANY) ABOUT NEW MEDIA UPLOADED
                    $followusers = $this->globaldata['user']->followers()->get();
                    $this->notifyFollowersForNewMedia($series, $followusers, $newseriesmedias);

                    $data['type'] = 'success';
                    $data['caption'] = 'Your video queued up for processing.';

                }
                else {

                    $data['type'] = 'error';
                    $data['caption'] = isset($captionerror) ? $captionerror : 'Unable to upload your video at this moment. Kindly try again.';

                }


            }
            // INVALID SERIES
            else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
            
    }

    public function submit(Request $request) {

        // AJAX REQUEST
         if ($request->ajax()) {

            $data = [];
            $seriesid = intval($request->seriesid);
            $creatorid = intval($this->globaldata['user']->userid);
            $series = Series::ofuser($creatorid)->find($seriesid);

            if(!empty($series)) {

                // SERIES MEDIAS INSERT/UPDATE/DELETE
                $seriesmediaids                 = Input::get('seriesmediaid');
                $seriesmediaimmidiatepublish    = Input::get('seriesmediaimmidiatepublish');
                $seriesmediavideotypes          = Input::get('seriesmediavideotype');
				$seriesmediapublish_day			= Input::get('publish_day');
				$seriesmediaminute				= Input::get('seriesmediaminute');
				$seriesmediahour				= Input::get('seriesmediahour');
				
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
                $captionerror = 'Unable to save your data at this moment. Kindly try again.';
                $allok = true;
                $newseriesmedias = [];

                // LOOP ALL SERIES MEDIAS
                foreach($seriesmediatitles as $index => $seriesmediatitle) {

                    $seriesmediaid = $seriesmediaids[$index];
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
						$seriesmedia->publish_day = $seriesmediapublish_day[$index];
						$seriesmedia->publish_time = $seriesmediahour[$index].":".$seriesmediaminute[$index];
                        $seriesmedia->title = $seriesmediatitle;
                        $seriesmedia->isfile = $seriesmediavideotypes[$index];
                        $seriesmedia->fileurl = ($seriesmediavideotypes[$index] == 1) ? '' : $seriesmediavideourls[$index];
                        if ($seriesmediahasvideos[$index] == 1) {
                            $seriesmedia->thumbid = '';
                        }
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

                            if($seriesmediaid == 0) {
                                $newseriesmedias[] = $seriesmedia;
                            }

                            // UPLOAD VIDEO FILE IF SELECTED
                            if ($seriesmediahasvideos[$index] == 1) {

                                $videoindex = $seriesmediavideoindexs[$index];
                                $file = $seriesmediavideofiles[$videoindex];
                                $uploadresponse = $this->uploadVideo($seriesmedia, $file, $seriesmediaid);

                                if(!$uploadresponse['result']) {
                                    $allok = false;
                                    $captionerror = $uploadresponse['message'];
                                    break;
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

                if($allok) {

                    // NOTIFY FOLLOWERS (IF ANY) ABOUT NEW MEDIA UPLOADED
                    if($newmediauploaded) {
                        $followusers = $this->globaldata['user']->followers()->get();
                        $this->notifyFollowersForNewMedia($series, $followusers, $newseriesmedias);
                    }

                    $data['type'] = 'success';
                    $data['caption'] = 'Details saved successfully.';
                    $data['redirectUrl'] = url('/vlogs/edit');

                }
                else {

                    $data['type'] = 'error';
                    $data['caption'] = isset($captionerror) ? $captionerror : 'Some of the data not saved. Please try again.';

                }

            }
            // INVALID SERIES
            else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }  

    private function uploadVideo($seriesmedia, $file, $seriesmediaid) {

        $data = ['result' => true, 'message' => ''];
        // $filepath = public_path($seriesmedia->filedir);
        $filepath = base_path('public_html/'.$seriesmedia->filedir);
        
        $extension = $file->getClientOriginalExtension();

        // DELETE OLD VIDEO FILE
        File::deleteDirectory($filepath);
        File::makeDirectory($filepath, 0777, true, true);
        $fileName = getTempName($filepath, $extension);

        // VIDEO FILE UPLOAD SUCCESS
        if($file->move($filepath, $fileName)) {
            
            $seriesmedia->filename = $fileName;
            $seriesmedia->update();
            return $data;
            // if(!$seriesmedia->update()) {
            //     $data['result'] = false;
            //     $data['message'] = 'Some problem occured while updating video details into database.';
            // }
            // VIDEO FILE NAME UPDATE IN SERIESMEDIA TABLE FAIL
            if($seriesmedia->update()) {

                $client_id = env('CLIENT_ID');
                $client_secret = env('CLIENT_SECRET');
                $access_token = env('ACCESS_TOKEN');
                
                $videouploaded = false;
                $url = $seriesmedia->filepath;
                //$url = 'https://www.w3schools.com/html/mov_bbb.mp4';

                $lib = new \Vimeo\Vimeo($client_id, $client_secret);
                $lib->setToken($access_token);

                // EDIT 
                if($seriesmediaid > 0) {

                    // LAST VIDEO IS VIMEO
                    if(trim($seriesmedia->videoid) != '') {
                        $video_response = $lib->request('/videos'.'/'.trim($seriesmedia->videoid), [], 'DELETE');

                        if(isset($video_response['status']) && $video_response['status'] == 204) {

                            $video_response = $lib->request('/me/videos', ['type' => 'pull', 'link' => $url], 'POST');
                            $videouploaded = true;

                        }

                        
                    }
                    // LAST VIDEO IS NOT VIMEO
                    else {
                        $video_response = $lib->request('/me/videos', ['type' => 'pull', 'link' => $url], 'POST');
                        $videouploaded = true;
                    }

                }
                // ADD
                else {

                    $video_response = $lib->request('/me/videos', ['type' => 'pull', 'link' => $url], 'POST');
                    $videouploaded = true;

                }

                // UPLOAD SUCCESS
                if($videouploaded) {

                    if(isset($video_response['status']) && $video_response['status'] == 200) {
                        
                        $videoid = ltrim($video_response['body']['uri'], ('/'.'videos/'));
                        $seriesmedia->videoid = $videoid;
                        $seriesmedia->isfile = 0;
                        $seriesmedia->isprocessing = 1;
                        $seriesmedia->fileurl = 'https://player.vimeo.com/video/'.$videoid;

                        if(!$seriesmedia->update()) {
                            $data['result'] = false;
                            $data['message'] = 'Some problem occured while updating video details into database.';
                        }

                    }
                    else {
                        
                        $data['result'] = false;
                        
                        if(isset($video_response['body']['error'])) {
                            $data['message'] = $video_response['body']['error'];
                        }
                        else {
                            $data['message'] = 'Unable to upload video to the server.';
                        }

                    }

                }
                else {

                    $data['result'] = false;
                    $data['message'] = 'Unable to upload video to the server.';

                }


            }
            else {

                $data['result'] = false;
                $data['message'] = 'Some problem occured while uploading video.';

            }

        }
        // VIDEO FILE UPLOAD FAIL
        else {

            $data['result'] = false;
            $data['message'] = 'Unable to upload video.';

        }

        return $data;
    }

    private function notifyFollowersForNewMedia($series, $followusers, $newseriesmedias) {
        
        $sendmailresult = true;

        if(!$followusers->isEmpty() && !empty($series)) {

            $user = $this->globaldata['user'];
            $emailtemplateid = config('app.constants.emailtemplates.notifyfollowersfornewmedia');    
            $serieslink = url('series/'.$series->slug);

            foreach($followusers as $followuser) {

                $follower = $followuser->follower()->active()->get();
                $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();

                $data['userid'] = $follower->first()->userid;
                if(!$follower->isEmpty()) {
                    foreach($newseriesmedias as $seriesmedia) {
                        $notification = new Notification();
                        $notification->type = 4;
                        $notification->userid = $follower->first()->userid;
                        $notification->contentid = $seriesmedia->seriesmediaid;
                        $notification->save();    
                    }
                }

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

    public function destroymedia(Request $request) {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $seriesmediaid = intval($request->seriesmediaid);
            $seriesmedia = Seriesmedia::find($seriesmediaid);

            // IF SERIES MEDIA TO BE DELETED EXISTS
            if(!empty($seriesmedia)) {

                $data['type'] = 'error';
                $data['caption'] = 'Unable to delete video. Please try again.';
                $alldeleted = true;

                // DELETE FROM MEDIACLICK TABLE
                if(!$seriesmedia->seriesmediaclicks()->get()->isEmpty()) {
                    $alldeleted = Mediaclick::destroy(array_column($seriesmedia->seriesmediaclicks()->get()->toArray(), 'mediaclickid'));
                }

                if($alldeleted) {

                    // DELETE FROM NOTIFICATIONS TABLE
                    $newvideopostednotifications = array_column(Notification::ofnewvideo()->where('contentid', $seriesmedia->seriesmediaid)->get()->toArray(), 'notificationid');
                    $commentnotifications = array_column(Notification::ofcomment()->whereIn('contentid', array_column($seriesmedia->seriesmediacomments()->get()->toArray(), 'seriesmediacommentid'))->get()->toArray(), 'notificationid');
                    $likenotifications = array_column(Notification::oflike()->whereIn('contentid', array_column($seriesmedia->seriesmedialikesdislikes()->likes()->get()->toArray(), 'seriesmedialikesdislikeid'))->get()->toArray(), 'notificationid');
                    $notificationids_todelete = array_unique(array_merge($newvideopostednotifications, $commentnotifications, $likenotifications));

                    if(!empty($notificationids_todelete)) {
                        $alldeleted = Notification::destroy($notificationids_todelete);
                    }

                    if($alldeleted) {

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
                                //if($seriesmedia->hasfile) {
                                if(file_exists(public_path() . $seriesmedia->filesdir)) {
                                    $alldeleted = File::deleteDirectory(public_path($seriesmedia->filesdir));
                                }

                                if($alldeleted) {

                                    // DELETE FROM VIMEO SERVER
                                    if(trim($seriesmedia->videoid) != '') {

                                        $client_id = env('CLIENT_ID');
                                        $client_secret = env('CLIENT_SECRET');
                                        $access_token = env('ACCESS_TOKEN');

                                        $lib = new \Vimeo\Vimeo($client_id, $client_secret);
                                        $lib->setToken($access_token);
                                        $video_response = $lib->request('/videos'.'/'.trim($seriesmedia->videoid), [], 'DELETE');                                    

                                    }

                                    if($alldeleted) {

                                        // DELETE SERIES MEDIA
                                        if($seriesmedia->delete()) {

                                            $data['type'] = 'success';
                                            $data['caption'] = 'Air deleted successfully.';

                                        }

                                    }

                                }

                            }
                            
                        }

                    }

                }

            }
            else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    /*public function destroy(Request $request) {
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
    }*/
    public function addcommentn(Request $request) {
        $data = [];
        $data['type'] = 'error';
         return response()->json($data);
    }
/*
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

                    if($seriesmedia->series->userid != $commentorid) {
                        $notification = new Notification();
                        $notification->type = 2;
                        $notification->userid = $seriesmedia->series->userid;
                        $notification->contentid = $seriesmediacomment->seriesmediacommentid;
                        $notification->save();
                    }

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

    } */

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

    // public function deletecomment(Request $request) {

    //     $data = [];
    //     $data['type'] = 'error';
        
    //     // MAKE VALIDATION RULES FOR RECEIVED DATA
    //     $rules = [
    //         'seriesmediacommentid'   => 'required'
    //     ];

    //     // VALIDATE RECEIVED DATA
    //     $validator = Validator::make($request->all(), $rules);

    //     // VALIDATION SUCCESS
    //     if(!$validator->fails()) {

    //         $seriesmediacommentid = intval($request->seriesmediacommentid);
    //         $seriesmediacomment = Seriesmediacomment::active()->find($seriesmediacommentid);

    //         if(!empty($seriesmediacomment)) {

    //             if($seriesmediacomment->seriesmedia->series->userid == $this->globaldata['user']->userid) {

    //                 $allok = true;
    //                 $notifications = Notification::ofcomment()->where('contentid', $seriesmediacomment->seriesmediacommentid)->get();
    //                 if(!$notifications->isEmpty()) {
    //                     $notification = $notifications->first();
    //                     if(!$notification->delete()) {
    //                         $allok = false;
    //                     }
    //                 }

    //                 if($allok) {

    //                     if($seriesmediacomment->delete()) {
    //                         $data['type'] = 'success';
    //                     }
    //                     else {
    //                         $data['type'] = 'error';
    //                         $data['caption'] = 'Unable to delete the comment at this moment. Please try again.';
    //                     }

    //                 }
    //                 else {
    //                     $data['type'] = 'error';
    //                     $data['caption'] = 'Unable to delete the comment at this moment. Please try again.';
    //                 }
                    
    //             }
    //             else {
    //                 $data['type'] = 'error';
    //                 $data['caption'] = 'Invalid comment.';
    //             }

    //         }
    //         else {
    //             $data['type'] = 'error';
    //             $data['caption'] = 'Invalid comment.';
    //         }

    //     }
    //     // VALIDATION FAIL
    //     else {

    //         $errors = $validator->errors()->all();
    //         $data['type'] = 'error';
    //         $data['caption'] = 'One or more invalid input found.';
    //         $data['errorfields'] = $validator->errors()->keys();

    //     }

    //     return response()->json($data);

    // }



    public function upload_days(){
        return view('upload_days');    
    }
    
    public function addnewupload(Request $request)
    {

        if (1) {

            $data = [];
            $loggedinuserid = $this->globaldata['user']->userid;
            $series = Series::ofuser($loggedinuserid)->get();
           /*  echo "<pre> helloo";
			print_r($request);
			echo "</pre>";
			exit(); */
            if(!$series->isEmpty()) {

                $series = $series->first();

                // SERIES MEDIAS INSERT/UPDATE/DELETE
                $seriesmediavideofile           = $request->file('seriesmediavideofile');
                $seriesmediatitle               = $request->seriesmediatitle;
                $seriesmediaimmidiatepublish    = $request->seriesmediaimmidiatepublish;
                $seriesmediadate                = intval($request->seriesmediadate);
                $seriesmediamonth               = intval($request->seriesmediamonth);
                $seriesmediahour                = intval($request->seriesmediahour);
                $seriesmediaminute              = intval($request->seriesmediaminute);
				$seriesmediapublish_day			= $request->seriesmediapublish_day;
                $newmediauploaded = false;
                $allok = true;
                $newseriesmedias = [];

                require_once(__DIR__.'/../../../vendor/james-heinrich/getid3/getid3/getid3.php');
                $getID3 = new \getID3;
                if(empty( $seriesmediavideofile)){
                    $data['type'] = 'error';
                    $data['caption'] = 'Your video not upload.';
                    return response()->json($data);
                }
                $ThisFileInfo = $getID3->analyze($seriesmediavideofile->getPathName());
                $time = $ThisFileInfo['playtime_string'];

                $split_time = explode(':', $time);
                $modifier = pow(60, count($split_time) - 1);
                $seconds = 0;
                foreach($split_time as $time_part){
                    $seconds += ($time_part * $modifier);
                    $modifier /= 60;
                }
                if($seconds > 60){
                    $data['type'] = 'error';
                    $data['caption'] = 'Your video has been over 60 seconds.';

                    return response()->json($data);
                }

                $seriesmedia = new Seriesmedia();
                $seriesmedia->title = $seriesmediatitle;
                $seriesmedia->isfile = 1;
                $seriesmedia->status = 1;
                $seriesmedia->immidiatepublish = $seriesmediaimmidiatepublish;
				$seriesmedia->publish_day = $seriesmediapublish_day;
				$seriesmedia->publish_time = $seriesmediahour. ':' .$seriesmediaminute;
				
				 $user = new User();
				$user = User::find($loggedinuserid);
				$user->publish_day    =  $seriesmediapublish_day;
				$user->publish_time        = $seriesmediahour. ':' .$seriesmediaminute;
				$result = $user->update();
				
                if($seriesmedia->immidiatepublish == 0) {

                    $nextDate = new DateTime();

                    $nextDate->modify('next '. $seriesmediapublish_day);

                    $todayDate = new DateTime();
                    $diff=date_diff($nextDate,$todayDate); 

                    if($diff->format("%a") == 6) {

                        $seriesmedia->publishdate = $todayDate->format('Y-m-d') . ' ' . $seriesmediahour . ':' . $seriesmediaminute . ':00';

                    }
                    else {

                        $seriesmedia->publishdate = $nextDate->format('Y-m-d') . ' ' . $seriesmediahour . ':' . $seriesmediaminute . ':00';
                    }
                }

                // SERIES MEDIA INSERT/UPDATE SUCCESS
                if($series->seriesmedias()->save($seriesmedia)) {

                    $newseriesmedias[] = $seriesmedia;
                    $file = $seriesmediavideofile;
                    $seriesmediaid = 0;
                    $uploadresponse = $this->uploadVideo($seriesmedia, $file, $seriesmediaid);

                    if(!$uploadresponse['result']) {
                        $allok = false;
                        $captionerror = $uploadresponse['message'];
                    }

                }

                

                if($allok) {

                    // NOTIFY FOLLOWERS (IF ANY) ABOUT NEW MEDIA UPLOADED
                    $followusers = $this->globaldata['user']->followers()->get();
                    $this->notifyFollowersForNewMedia($series, $followusers, $newseriesmedias);
					$data['redirectUrl'] = url('/vlogs/edit');
                    $data['type'] = 'success';
                    $data['caption'] = 'Your video queued up for processing.';

                }
                else {

                    $data['type'] = 'error';
                    $data['caption'] = isset($captionerror) ? $captionerror : 'Unable to upload your video at this moment. Kindly try again.';

                }


            }
            // INVALID SERIES
            else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }
}