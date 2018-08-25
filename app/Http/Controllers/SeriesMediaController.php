<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontpostloginbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\PushNotification;
use App\User;
use App\Series;
use App\Seriesmedia;
use App\Truefriend;
use App\Seriesmedialikedislike;
use App\Seriesmediaadminlikedislike;
use App\Seriesmediacomment;
use App\Emailtemplate;
use App\Followuser;
use App\Notification;
use App\Notificationlog;
use App\Mediaclick;
use App\Requestmedia;
use Carbon\Carbon;
use \Datetime;

class SeriesMediaController extends FrontbaseController
{

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

    public function upload()
    {
        return view('upload');
    }


    public function edit()
    {

        $data = [];


        $loggedinuserid = $this->globaldata['user']->userid;
        $series = Series::ofuser($loggedinuserid)->get();
        if (!$series->isEmpty()) {
            $series = $series->first();
            $data['series'] = $series;

            $seriesmedias = [];
            $seriesmediasresult = $series->seriesmedias()->orderBy('seriesmediaid', 'desc')->get();
            foreach ($seriesmediasresult as $seriesmedia) {
                if ($seriesmedia->isprocessing == 1 && $seriesmedia->videoid != '') {
                    $seriesmedia->updateProcessingStatus();
                }
                $seriesmedias[] = $seriesmedia;
            }

            $data['seriesmedias'] = $seriesmedias;
            $data['seriesmediacounts'] = count($seriesmedias);
            $data['followusercount'] = intval($this->globaldata['user']->followerinfluencer) + intval($this->globaldata['user']->followers()->count());
			$data['truefc'] = count(Truefriend::where('friend1_id',$this->globaldata['user']->userid)->orWhere('friend2_id',  $this->globaldata['user']->userid)->get());
            
			return view('seriesadd', $data);
        } else {
            return view('errors.404');
        }
    }


    public function addnew(Request $request)
    {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $loggedinuserid = $this->globaldata['user']->userid;
            $series = Series::ofuser($loggedinuserid)->get();
            $newseriesmedias = [];

            if (!$series->isEmpty()) {

                $series = $series->first();

                // SERIES MEDIAS INSERT/UPDATE/DELETE

                $seriesmediavideofile = $request->file('seriesmediavideofile');
                $seriesmediatitle = $request->seriesmediatitle;
                $seriesmediaimmidiatepublish = $request->seriesmediaimmidiatepublish;
                $seriesmediadate = intval($request->seriesmediadate);
                $seriesmediamonth = intval($request->seriesmediamonth);
                $seriesmediahour = intval($request->seriesmediahour);
                $seriesmediaminute = intval($request->seriesmediaminute);

                $newmediauploaded = false;
                $allok = true;


                // echo $seriesmediatitle;
                $seriesmedia = new Seriesmedia();
                $seriesmedia->title = $seriesmediatitle;
                $seriesmedia->isfile = 1;
                $seriesmedia->status = 1;
                $seriesmedia->immidiatepublish = $seriesmediaimmidiatepublish;
                if ($seriesmedia->immidiatepublish == 0) {
                    $seriesmedia->publishdate = date('Y') . '-' . $seriesmediamonth . '-' . $seriesmediadate . ' ' . $seriesmediahour . ':' . $seriesmediaminute . ':00';
                }

                // SERIES MEDIA INSERT/UPDATE SUCCESS
                if ($series->seriesmedias()->save($seriesmedia)) {

                    $newseriesmedias[] = $seriesmedia;
                    $file = $seriesmediavideofile;
                    $seriesmediaid = 0;
                    $uploadresponse = $this->uploadVideo($seriesmedia, $file, $seriesmediaid);

                    if (!$uploadresponse['result']) {
                        $allok = false;
                        $captionerror = $uploadresponse['message'];
                    }

                }

                if ($allok) {

                    // NOTIFY FOLLOWERS (IF ANY) ABOUT NEW MEDIA UPLOADED
                    $followusers = $this->globaldata['user']->followers()->get();
                    $this->notifyFollowersForNewMedia($series, $followusers, $newseriesmedias);

                    $data['type'] = 'success';
                    $data['caption'] = 'Your video queued up for processing.';

                } else {

                    $data['type'] = 'error';
                    $data['caption'] = isset($captionerror) ? $captionerror : 'Unable to upload your video at this moment. Kindly try again.';

                }


            } // INVALID SERIES
            else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }

    }

    public function submit(Request $request)
    {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $seriesid = intval($request->seriesid);
            $creatorid = intval($this->globaldata['user']->userid);
            $series = Series::ofuser($creatorid)->find($seriesid);

            if (!empty($series)) {

                // SERIES MEDIAS INSERT/UPDATE/DELETE
                $seriesmediaids = Input::get('seriesmediaid');
                $seriesmediaimmidiatepublish = Input::get('seriesmediaimmidiatepublish');
                $seriesmediavideotypes = Input::get('seriesmediavideotype');
                $seriesmediapublish_day = Input::get('publish_day');
                $seriesmediaminute = Input::get('seriesmediaminute');
                $seriesmediahour = Input::get('seriesmediahour');

                $seriesmediahasvideos = Input::get('seriesmediahasvideo');
                $seriesmediavideoindexs = Input::get('seriesmediavideoindex');
                $seriesmediahasvideothumbs = Input::get('seriesmediahasvideothumb');
                $seriesmediavideothumbindexs = Input::get('seriesmediavideothumbindex');

                $seriesmediatitles = empty(Input::get('seriesmediatitle')) ? [] : Input::get('seriesmediatitle');
                $seriesmediadescriptions = Input::get('seriesmediadescription');
                $seriespublishdates = Input::get('seriespublishdate');
                $seriesmediavideofiles = $request->file('seriesmediavideofile');
                $seriesmediavideourls = Input::get('seriesmediavideourl');
                $seriesmediavideothumbfiles = $request->file('seriesmediavideothumbfile');

                $newmediauploaded = false;
                $captionerror = 'Unable to save your data at this moment. Kindly try again.';
                $allok = true;
                $newseriesmedias = [];

                // LOOP ALL SERIES MEDIAS
                foreach ($seriesmediatitles as $index => $seriesmediatitle) {

                    $seriesmediaid = $seriesmediaids[$index];
                    $seriesmedia = ($seriesmediaid == 0) ? new Seriesmedia() : Seriesmedia::find($seriesmediaid);

                    // IF SERIES MEDIA IS VALID
                    if (!empty($seriesmedia)) {

                        // IF EDIT CASE AND NEW VIDEO TYPE SELECTED IS URL
                        if ($seriesmediavideotypes[$index] == 0 && $seriesmediaid > 0) {
                            if ($seriesmedia->hasfile) {
                                $filespath = public_path($seriesmedia->filesdir);
                                File::deleteDirectory($filespath);
                            }
                        }

                        // UPDATE SERIES MEDIAS NEW VALUE
                        $seriesmedia->publish_day = $seriesmediapublish_day[$index];
                        $seriesmedia->publish_time = $seriesmediahour[$index] . ":" . $seriesmediaminute[$index];
                        $seriesmedia->title = $seriesmediatitle;
                        $seriesmedia->isfile = $seriesmediavideotypes[$index];
                        $seriesmedia->fileurl = ($seriesmediavideotypes[$index] == 1) ? '' : $seriesmediavideourls[$index];
                        if ($seriesmediahasvideos[$index] == 1) {
                            $seriesmedia->thumbid = '';
                        }
                        $seriesmedia->status = 1;

                        $seriesmedia->immidiatepublish = intval($seriesmediaimmidiatepublish[$index]);
                        if ($seriesmedia->immidiatepublish == 1) {
                            $seriesmedia->publishdate = null;
                        } else {
                            $seriesmedia->publishdate = $seriespublishdates[$index];
                        }

                        // SERIES MEDIA INSERT/UPDATE SUCCESS
                        if ($series->seriesmedias()->save($seriesmedia)) {

                            if ($seriesmediaid == 0) {
                                $newseriesmedias[] = $seriesmedia;
                            }

                            // UPLOAD VIDEO FILE IF SELECTED
                            if ($seriesmediahasvideos[$index] == 1) {

                                $videoindex = $seriesmediavideoindexs[$index];
                                $file = $seriesmediavideofiles[$videoindex];
                                $uploadresponse = $this->uploadVideo($seriesmedia, $file, $seriesmediaid);

                                if (!$uploadresponse['result']) {
                                    $allok = false;
                                    $captionerror = $uploadresponse['message'];
                                    break;
                                }

                            }

                        } // SERIES MEDIA INSERT/UPDATE FAIL
                        else {

                            $allok = false;

                        }

                    }

                    if ($allok) {
                        if ($seriesmediaid == 0) {
                            $newmediauploaded = true;
                        }
                    }

                }

                if ($allok) {

                    // NOTIFY FOLLOWERS (IF ANY) ABOUT NEW MEDIA UPLOADED
                    if ($newmediauploaded) {
                        $followusers = $this->globaldata['user']->followers()->get();
                        $this->notifyFollowersForNewMedia($series, $followusers, $newseriesmedias);

                        //Push notific

                    }

                    $data['type'] = 'success';
                    $data['caption'] = 'Details saved successfully.';
                    $data['redirectUrl'] = url('/vlogs/edit');

                } else {

                    $data['type'] = 'error';
                    $data['caption'] = isset($captionerror) ? $captionerror : 'Some of the data not saved. Please try again.';

                }

            } // INVALID SERIES
            else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function makeMediaAvailable(Request $request)
    {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $seriesmediaid = intval($request->seriesmediaid);
            $seriesmedia = Seriesmedia::find($seriesmediaid);

            $captionerror = 'Unable to make your video available';
            $allok = true;

            // IF SERIES MEDIA IS VALID
            if (!empty($seriesmedia)) {

                $seriesmedia->publishdate = Carbon::now()->format('Y-m-d H:i:s');

                // SERIES MEDIA UPDATE FAIL
                if (!$seriesmedia->update()) {
                    $allok = false;
                }
            } else {
                $allok = false;
            }

            if ($allok) {
                $data['type'] = 'success';
                $data['caption'] = 'Video is available now.';
                $data['redirectUrl'] = url('/vlogs/edit');
            } else {
                $data['type'] = 'error';
                $data['caption'] = $captionerror;
            }

            return response()->json($data);
        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }


    private function uploadVideo($seriesmedia, $file, $seriesmediaid)
    {

        $data = ['result' => true, 'message' => ''];
        $filepath = public_path($seriesmedia->filedir);
        $extension = $file->getClientOriginalExtension();

        // DELETE OLD VIDEO FILE
        File::deleteDirectory($filepath);
        File::makeDirectory($filepath, 0777, true, true);
        $fileName = getTempName($filepath, $extension);

        // VIDEO FILE UPLOAD SUCCESS
        if ($file->move($filepath, $fileName)) {

            $seriesmedia->filename = $fileName;
            $seriesmedia->update();
            return $data;
            // if(!$seriesmedia->update()) {
            //     $data['result'] = false;
            //     $data['message'] = 'Some problem occured while updating video details into database.';
            // }
            // VIDEO FILE NAME UPDATE IN SERIESMEDIA TABLE FAIL
            if ($seriesmedia->update()) {

                $client_id = env('CLIENT_ID');
                $client_secret = env('CLIENT_SECRET');
                $access_token = env('ACCESS_TOKEN');

                $videouploaded = false;
                $url = $seriesmedia->filepath;
                //$url = 'https://www.w3schools.com/html/mov_bbb.mp4';

                $lib = new \Vimeo\Vimeo($client_id, $client_secret);
                $lib->setToken($access_token);

                // EDIT
                if ($seriesmediaid > 0) {

                    // LAST VIDEO IS VIMEO
                    if (trim($seriesmedia->videoid) != '') {
                        $video_response = $lib->request('/videos' . '/' . trim($seriesmedia->videoid), [], 'DELETE');

                        if (isset($video_response['status']) && $video_response['status'] == 204) {

                            $video_response = $lib->request('/me/videos', ['type' => 'pull', 'link' => $url], 'POST');
                            $videouploaded = true;

                        }


                    } // LAST VIDEO IS NOT VIMEO
                    else {
                        $video_response = $lib->request('/me/videos', ['type' => 'pull', 'link' => $url], 'POST');
                        $videouploaded = true;
                    }

                } // ADD
                else {

                    $video_response = $lib->request('/me/videos', ['type' => 'pull', 'link' => $url], 'POST');
                    $videouploaded = true;

                }

                // UPLOAD SUCCESS
                if ($videouploaded) {

                    if (isset($video_response['status']) && $video_response['status'] == 200) {

                        $videoid = ltrim($video_response['body']['uri'], ('/' . 'videos/'));
                        $seriesmedia->videoid = $videoid;
                        $seriesmedia->isfile = 0;
                        $seriesmedia->isprocessing = 1;
                        $seriesmedia->fileurl = 'https://player.vimeo.com/video/' . $videoid;

                        if (!$seriesmedia->update()) {
                            $data['result'] = false;
                            $data['message'] = 'Some problem occured while updating video details into database.';
                        }

                    } else {

                        $data['result'] = false;

                        if (isset($video_response['body']['error'])) {
                            $data['message'] = $video_response['body']['error'];
                        } else {
                            $data['message'] = 'Unable to upload video to the server.';
                        }

                    }

                } else {

                    $data['result'] = false;
                    $data['message'] = 'Unable to upload video to the server.';

                }


            } else {

                $data['result'] = false;
                $data['message'] = 'Some problem occured while uploading video.';

            }

        } // VIDEO FILE UPLOAD FAIL
        else {

            $data['result'] = false;
            $data['message'] = 'Unable to upload video.';

        }

        return $data;
    }

    private function notifyFollowersForNewMedia($series, $followusers, $newseriesmedias)
    {

        $sendmailresult = true;

        if (!$followusers->isEmpty() && !empty($series)) {

            $user = $this->globaldata['user'];
            $emailtemplateid = config('app.constants.emailtemplates.notifyfollowersfornewmedia');
            $serieslink = url('series/' . $series->slug);

            foreach ($followusers as $followuser) {

                $follower = $followuser->follower()->active()->get();
                $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();

                if (!$follower->isEmpty()) {
                    foreach ($newseriesmedias as $seriesmedia) {
                        $notification = new Notification();
                        $notification->type = 4;
                        $notification->userid = $follower->first()->userid;
                        $notification->contentid = $seriesmedia->seriesmediaid;
                        $notification->save();

                        //push Notification
                        // $title = 'Potatoes';
                        // $air_msg = $this->globaldata['user']->fullname." just aired a new post";

                        //  $air_msgPayload = array(
                        //     'mtitle'    => $title,
                        //     'mdesc'     => $air_msg,
                        // );

                        // $air_PNO = new PushNotification();
                        // $air_receiverUserToken = User::find($follower->first()->userid);
                        // $air_registration_id = $air_receiverUserToken['registration_id'];
                        // $data['comment_registration_id'] = $air_registration_id;
                        // $air_access_token = $air_receiverUserToken['access_token'];
                        // $air_status = '';
                        // if(!empty($air_access_token))
                        // {
                        //     $air_status = $air_PNO->iOS($air_msgPayload,$air_access_token,$pathPerm);
                        // }
                        // if(!empty($air_registration_id))
                        // {
                        //     $air_status = $air_PNO->android($air_msgPayload,$air_registration_id);
                        // }

                        // $air_notifylog = new Notificationlog();
                        // $air_notifylog->title = $title;
                        // $air_notifylog->message = $air_msg;
                        // $air_notifylog->userid = $follower->first()->userid;
                        // $air_notifylog->status = $air_status;
                        // $air_notifylog->save();
                    }
                }

                if (!$emailtemplate->isEmpty() && !$follower->isEmpty()) {

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
                    Mail::send([], [], function ($message) use ($emailtemplate) {
                        $message->to($emailtemplate->mailto, $emailtemplate->mailtoname);
                        $message->from($emailtemplate->mailfrom, $emailtemplate->mailfromname);
                        $message->subject($emailtemplate->mailsubject);
                        $message->setBody($emailtemplate->mailbody, 'text/html');
                        if (!empty($emailtemplate->replyto)) {
                            $message->replyTo($emailtemplate->replyto, $emailtemplate->replytoname);
                        }
                        if (!empty($emailtemplate->ccto)) {
                            $message->cc($emailtemplate->ccto, $emailtemplate->cctoname);
                        }
                        if (!empty($emailtemplate->bccto)) {
                            $message->bcc($emailtemplate->bccto, $emailtemplate->bcctoname);
                        }
                    });

                    // MAIL SENDING FAIL
                    if (count(Mail::failures()) > 0) {
                        $sendmailresult = false;
                    }

                }
            }
        }

        return $sendmailresult;
    }

    private function uploadSeriesImage($series, $request)
    {
        $result = false;
        $filepath = public_path($series->filedir);

        // UPLOAD IMAGE FILE IF EXIST
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {

                // DELETE OLD FILE
                File::deleteDirectory($filepath);

                $extension = $file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->fit(config('app.constants.series_image_width'), config('app.constants.series_image_height'), function ($constraint) {
                    $constraint->upsize();
                });
                $filecreated = File::makeDirectory($filepath, 0777, true, true);

                if ($filecreated) {
                    $fileName = getTempName($filepath, $extension);
                    if ($img->save($filepath . $fileName)) {
                        $series->filename = $fileName;
                        if ($series->update()) {
                            $result = true;
                        }
                    }
                }
            }
        } else {
            $result = true;
        }
        return $result;
    }

    // Check slug and retun unique slug
    public function checkSlug($slug)
    {
        $res = Series::Where('slug', $slug)->latest()->first();
        if (count($res) > 0) {
            $slug_array = explode('-', $res->slug);
            $last = (int)$slug_array[count($slug_array) - 1];
            if ($last > 0) {
                array_pop($slug_array);
                $count = $last;
            } else {
                $count = 0;
            }
            $count++;
            $new_slug = implode('-', $slug_array) . '-' . $count;
            $res = Series::Where('slug', $new_slug)->latest()->first();
            if (count($res) > 0) {
                $new_slug = $this->checkSlug($new_slug);
            }
            return $new_slug;
        } else {
            return $slug;
        }
    }

    public function destroymedia(Request $request)
    {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $seriesmediaid = intval($request->seriesmediaid);
            $seriesmedia = Seriesmedia::find($seriesmediaid);

            // IF SERIES MEDIA TO BE DELETED EXISTS
            if (!empty($seriesmedia)) {

                $data['type'] = 'error';
                $data['caption'] = 'Unable to delete video. Please try again.';
                $alldeleted = true;

                // DELETE FROM MEDIACLICK TABLE
                if (!$seriesmedia->seriesmediaclicks()->get()->isEmpty()) {
                    $alldeleted = Mediaclick::destroy(array_column($seriesmedia->seriesmediaclicks()->get()->toArray(), 'mediaclickid'));
                }

                if ($alldeleted) {

                    // DELETE FROM NOTIFICATIONS TABLE
                    $newvideopostednotifications = array_column(Notification::ofnewvideo()->where('contentid', $seriesmedia->seriesmediaid)->get()->toArray(), 'notificationid');
                    $commentnotifications = array_column(Notification::ofcomment()->whereIn('contentid', array_column($seriesmedia->seriesmediacomments()->get()->toArray(), 'seriesmediacommentid'))->get()->toArray(), 'notificationid');
                    $likenotifications = array_column(Notification::oflike()->whereIn('contentid', array_column($seriesmedia->seriesmedialikesdislikes()->likes()->get()->toArray(), 'seriesmedialikesdislikeid'))->get()->toArray(), 'notificationid');
                    $mediaReuest = Notification::where([
                        'contentid' => $seriesmediaid,
                        'type' => 5
                    ])->delete();
                    $get_all_likes = Seriesmedia::find($seriesmediaid)->seriesmedialikesdislikes()->get();
                    foreach ($get_all_likes as $get_all_like) {
                        $l_id = $get_all_like->seriesmedialikesdislikeid;
                        Notification::where('contentid', $l_id)->delete();
                    }
                    $get_all_comments = Seriesmedia::find($seriesmediaid)->seriesmediacomments()->get();
                    foreach ($get_all_comments as $get_all_comment) {
                        $c_id = $get_all_comment->seriesmediacommentid;
                        Notification::where('contentid', $c_id)->delete();
                    }
                    // $likepostnotification = Notification::destroy(array_column($seriesmedia->seriesmedialikesdislikes()->get()->toArray(), 'seriesmedialikesdislikeid'));
                    // $commentpostnotification = Notification::destroy(array_column($seriesmedia->seriesmediacomments()->get()->toArray(), 'seriesmediacommentid'));
                    $mediaReuestFollower = Notification::where([
                        'contentid' => $seriesmediaid,
                        'type' => 8
                    ])->delete();
                    $mediaReuestTable = Requestmedia::where('seriesmediaid', $seriesmediaid)->delete();
                    $notificationids_todelete = array_unique(array_merge($newvideopostednotifications, $commentnotifications, $likenotifications));

                    if (!empty($notificationids_todelete)) {
                        $alldeleted = Notification::destroy($notificationids_todelete);
                    }

                    //Delete Image File
                    $getimagefile = $seriesmedia->imagefile;
                    if ($getimagefile) {
                        unlink('uploads/' . $getimagefile);
                    }

                    //Delete Video Thumbnail
                    $getvideoThumbfile = $seriesmedia->filethumbname;
                    if ($getvideoThumbfile) {
                        unlink('videothumb/' . $getvideoThumbfile);
                    }

                    if ($alldeleted) {

                        // DELETE FROM SERIESMEDIACOMMENTS TABLE
                        if (!$seriesmedia->seriesmediacomments()->get()->isEmpty()) {
                            $alldeleted = Seriesmediacomment::destroy(array_column($seriesmedia->seriesmediacomments()->get()->toArray(), 'seriesmediacommentid'));
                        }

                        if ($alldeleted) {

                            // DELETE FROM SERIESMEDIALIKESDISLIKES TABLE
                            if (!$seriesmedia->seriesmedialikesdislikes()->get()->isEmpty()) {
                                $alldeleted = Seriesmedialikedislike::destroy(array_column($seriesmedia->seriesmedialikesdislikes()->get()->toArray(), 'seriesmedialikesdislikeid'));
                            }

                            if ($alldeleted) {

                                // DELETE SERIES MEDIA FILES IF ANY
                                //if($seriesmedia->hasfile) {
                                if (file_exists(public_path() . $seriesmedia->filesdir)) {
                                    $alldeleted = File::deleteDirectory(public_path($seriesmedia->filesdir));
                                }

                                if ($alldeleted) {

                                    // DELETE FROM VIMEO SERVER
                                    if (trim($seriesmedia->videoid) != '') {

                                        $client_id = env('CLIENT_ID');
                                        $client_secret = env('CLIENT_SECRET');
                                        $access_token = env('ACCESS_TOKEN');

                                        $lib = new \Vimeo\Vimeo($client_id, $client_secret);
                                        $lib->setToken($access_token);
                                        $video_response = $lib->request('/videos' . '/' . trim($seriesmedia->videoid), [], 'DELETE');

                                    }

                                    if ($alldeleted) {

                                        // DELETE SERIES MEDIA
                                        if ($seriesmedia->delete()) {

                                            $data['type'] = 'success';
                                            $data['caption'] = 'Air deleted successfully';

                                        }

                                    }

                                }

                            }

                        }

                    }

                }

            } else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        } // NON AJAX REQUEST
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

    public function addcommentn(Request $request)
    {
        $data = [];
        $data['type'] = 'error';

        // MAKE VALIDATION RULES FOR RECEIVED DATA
        $rules = [
            'seriesmediaid' => 'required',
            'comment' => 'required'
        ];

        // VALIDATE RECEIVED DATA
        $validator = Validator::make($request->all(), $rules);

        // VALIDATION SUCCESS
        if (!$validator->fails()) {

            $seriesmediaid = intval($request->seriesmediaid);
            $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

            if (!empty($seriesmedia)) {

                $commentorid = $this->globaldata['user']->userid;
                $comment = trim($request->comment);

                $seriesmediacomment = new Seriesmediacomment();
                $seriesmediacomment->comment = $comment;
                $seriesmediacomment->userid = $commentorid;
                $seriesmediacomment->status = 1;

                if ($seriesmedia->seriesmediacomments()->save($seriesmediacomment)) {

                    if ($seriesmedia->series->userid != $commentorid) {
                        $notification = new Notification();
                        $notification->type = 2;
                        $notification->userid = $seriesmedia->series->userid;
                        $notification->content_text = "1";
                        $notification->contentid = $seriesmediacomment->seriesmediacommentid;
                        $notification->save();

                        //push Notification
                        $title = 'Potatoes';
                        $comment_msg = $this->globaldata['user']->fullname . " just posted a comment";

                        $comment_msgPayload = array(
                            'mtitle' => $title,
                            'mdesc' => $comment_msg,
                        );

                        $comment_PNO = new PushNotification();
                        $comment_receiverUserToken = User::find($seriesmedia->series->userid);
                        $comment_registration_id = $comment_receiverUserToken->registration_id;
                        $data['comment_registration_id'] = $comment_registration_id;
                        $comment_access_token = $comment_receiverUserToken['access_token'];
                        $comment_status = '';
                        if (!empty($comment_access_token)) {
                            $comment_status = $comment_PNO->iOS($comment_msgPayload, $comment_access_token, $pathPerm);
                        }
                        if (!empty($comment_registration_id)) {
                            $comment_status = $comment_PNO->android($comment_msgPayload, $comment_registration_id);
                        }

                        // $notifylog_like = new Notificationlog();
                        // $notifylog_like->title = $title;
                        // $notifylog_like->message = $comment_msg;
                        // $notifylog_like->userid = $seriesmedia->series->userid;
                        // $notifylog_like->status = $comment_status;
                        // $notifylog_like->save();
                    }


                    $user_commentors = Followuser::where('userid', $seriesmedia->series->userid)->get();
                    foreach ($user_commentors as $user_commentor) {
                        $notification1 = new Notification();
                        $notification1->type = 7;
                        $notification1->userid = $user_commentor->followerid;
                        $notification1->comment_post_name = $seriesmedia->series->user->fullname;
                        $notification1->content_text = "";
                        $notification1->contentid = $seriesmediacomment->seriesmediacommentid;
                        $notification1->save();

                        //push Notification
                        $comment_PNO = new PushNotification();
                        $title = 'Potatoes';
                        $followers_comment_msg = "Someone just commented on " . $seriesmedia->series->user->fullname . "'s post";

                        $followers_comment_msgPayload = array(
                            'mtitle' => $title,
                            'mdesc' => $followers_comment_msg,
                        );
                        $followers_comment_receiverUserToken = User::find($user_commentor->followerid);
                        $followers_comment_registration_id = $followers_comment_receiverUserToken['registration_id'];
                        $data['comment_registration_id'] = $followers_comment_registration_id;
                        $followers_comment_access_token = $followers_comment_receiverUserToken['access_token'];
                        $followers_comment_status = '';
                        if (!empty($followers_comment_access_token)) {
                            $followers_comment_status = $comment_PNO->iOS($followers_comment_msgPayload, $followers_comment_access_token, $pathPerm);
                        }
                        if (!empty($followers_comment_registration_id)) {
                            $followers_comment_status = $comment_PNO->android($followers_comment_msgPayload, $followers_comment_registration_id);
                        }

                        // $notifylog_like = new Notificationlog();
                        // $notifylog_like->title = $title;
                        // $notifylog_like->message = $followers_comment_msg;
                        // $notifylog_like->userid = $user_commentor->followerid;
                        // $notifylog_like->status = $followers_comment_status;
                        // $notifylog_like->save();

                    }
                    $data['type'] = 'success';
                    $data['newcomment'] = view('seriesmediacomment', ['seriesmediacomment' => $seriesmediacomment])->render();
                } else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Unable to add your comment at this moment. Please try after some time.';
                }


            } else {
                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';
            }

        } // VALIDATION FAIL
        else {

            $errors = $validator->errors()->all();
            $data['type'] = 'error';
            $data['caption'] = 'One or more invalid input found.';
            $data['errorfields'] = $validator->errors()->keys();

        }

        return response()->json($data);
    }

    /*public function addcomment(Request $request) {

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

    public function getcomments(Request $request)
    {
        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $data['type'] = 'error';

            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid' => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if (!$validator->fails()) {

                $seriesmediaid = intval($request->seriesmediaid);
                $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

                if (!empty($seriesmedia)) {

                    if ($seriesmedia->series->userid == $this->globaldata['user']->userid) {
                        $seriesmediacomments = $seriesmedia->seriesmediacomments()->get();
                    } else {
                        $seriesmediacomments = Seriesmediacomment::where('seriesmediacommentid', 0)->get();
                    }

                    $data['type'] = 'success';
                    $data['html'] = view('ajax.seriesmediacomments', ['seriesmediacomments' => $seriesmediacomments])->render();
                    $data['title'] = $seriesmedia->title;

                } else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Invalid series media.';
                }

            } // VALIDATION FAIL
            else {

                $errors = $validator->errors()->all();
                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();

            }

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function deletecommentn(Request $request)
    {

        $data = [];
        $data['type'] = 'error';

        // MAKE VALIDATION RULES FOR RECEIVED DATA
        $rules = [
            'seriesmediacommentid' => 'required'
        ];

        // VALIDATE RECEIVED DATA
        $validator = Validator::make($request->all(), $rules);

        // VALIDATION SUCCESS
        if (!$validator->fails()) {

            $seriesmediacommentid = intval($request->seriesmediacommentid);
            $seriesmediacomment = Seriesmediacomment::active()->find($seriesmediacommentid);

            if (!empty($seriesmediacomment)) {

                if ($seriesmediacomment->seriesmedia->series->userid == $this->globaldata['user']->userid) {

                    $allok = true;
                    $notifications = Notification::ofcomment()->where('contentid', $seriesmediacomment->seriesmediacommentid)->get();
                    if (!$notifications->isEmpty()) {
                        $notification = $notifications->first();
                        if (!$notification->delete()) {
                            $allok = false;
                        }
                    }

                    $postcommentnotifications = Notification::where(['type' => '7', 'contentid' => $seriesmediacomment->seriesmediacommentid])->get();
                    foreach ($postcommentnotifications as $postcommentnotification) {
                        $fcomment_id = $postcommentnotification->notificationid;
                        Notification::where('notificationid', $fcomment_id)->delete();
                    }

                    if ($allok) {

                        if ($seriesmediacomment->delete()) {
                            $data['type'] = 'success';
                        } else {
                            $data['type'] = 'error';
                            $data['caption'] = 'Unable to delete the comment at this moment. Please try again.';
                        }

                    } else {
                        $data['type'] = 'error';
                        $data['caption'] = 'Unable to delete the comment at this moment. Please try again.';
                    }

                } else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Invalid comment.';
                }

            } else {
                $data['type'] = 'error';
                $data['caption'] = 'Invalid comment.';
            }

        } // VALIDATION FAIL
        else {

            $errors = $validator->errors()->all();
            $data['type'] = 'error';
            $data['caption'] = 'One or more invalid input found.';
            $data['errorfields'] = $validator->errors()->keys();

        }

        return response()->json($data);

    }

    public function liken(Request $request)
    {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $data['type'] = 'error';
            $data['count'] = 0;

            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid' => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if (!$validator->fails()) {

                $seriesmediaid = intval($request->seriesmediaid);
                $likevalue = intval($request->likevalue);
                $userid = $this->globaldata['user']->userid;
                $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

                if (!empty($seriesmedia)) {

                    // IF LIKE
                    if ($likevalue == 1) {

                        $liked = Seriesmedialikedislike::ofUser($userid)->likes()->where('seriesmediaid', $seriesmediaid)->get();
                        if ($liked->isEmpty()) {
                            $seriesmedialikedislike = new Seriesmedialikedislike();
                            $seriesmedialikedislike->seriesmediaid = $seriesmediaid;
                            $seriesmedialikedislike->userid = $userid;
                            $seriesmedialikedislike->type = 1;
                            if ($seriesmedialikedislike->save()) {

                                if ($seriesmedia->series->userid != $userid) {
                                    $notification = new Notification();
                                    $notification->type = 1;
                                    $notification->userid = $seriesmedia->series->userid;
                                    $notification->content_text = "1";
                                    $notification->contentid = $seriesmedialikedislike->seriesmedialikesdislikeid;
                                    $notification->save();

                                    //push Notification
                                    $title = 'Potatoes';
                                    $msg = $this->globaldata['user']->fullname . " just liked your post";

                                    $msgPayload = array(
                                        'mtitle' => $title,
                                        'mdesc' => $msg,
                                    );
                                    $PNO = new PushNotification();
                                    $receiverUserToken = User::find($notification->userid);
                                    $registration_id = $receiverUserToken->registration_id;
                                    $access_token = $receiverUserToken->access_token;
                                    $like_status = '';
                                    if (!empty($access_token)) {
                                        $like_status = $PNO->iOS($msgPayload, $access_token, $pathPerm);
                                    }
                                    if (!empty($registration_id)) {
                                        $like_status = $PNO->android($msgPayload, $registration_id);
                                    }

                                    // $notifylog_like = new Notificationlog();
                                    // $notifylog_like->title = $title;
                                    // $notifylog_like->message = $msg;
                                    // $notifylog_like->userid = $notification->userid;
                                    // $notifylog_like->status = $like_status;
                                    // $notifylog_like->save();

                                }

                                $user_followers = Followuser::where('userid', $seriesmedia->series->userid)->get();
                                foreach ($user_followers as $user_follower) {

                                    $notification1 = new Notification();
                                    $notification1->type = 6;
                                    $notification1->userid = $user_follower->followerid;
                                    $notification1->like_post_name = $seriesmedia->series->user->fullname;
                                    $notification1->content_text = "";
                                    $notification1->contentid = $seriesmedialikedislike->seriesmedialikesdislikeid;
                                    $notification1->save();

                                    // push notification
                                    $follower_user_like_msg = "Someone just liked " . $seriesmedia->series->user->fullname . "'s post";
                                    $title = 'Potatoes';
                                    $msgPayload = array(
                                        'mtitle' => $title,
                                        'mdesc' => $follower_user_like_msg,
                                    );

                                    $follower_user_like_token = User::find($user_follower->followerid);
                                    $follower_user_like_registration_id = $follower_user_like_token['registration_id'];
                                    $follower_user_like_access_token = $receiverUserToken['access_token'];
                                    $follower_user_like_status = '';
                                    if (!empty($follower_user_like_access_token)) {
                                        $follower_user_like_status = $PNO->iOS($msgPayload, $follower_user_like_access_token, $pathPerm);
                                    }
                                    if (!empty($registration_id)) {
                                        $follower_user_like_status = $PNO->android($msgPayload, $follower_user_like_registration_id);
                                    }

                                    // $followers_notifylog_like = new Notificationlog();
                                    // $followers_notifylog_like->title = $title;
                                    // $followers_notifylog_like->message = $follower_user_like_msg;
                                    // $followers_notifylog_like->userid = $user_follower->followerid;
                                    // $followers_notifylog_like->status = $follower_user_like_status;
                                    // $followers_notifylog_like->save();

                                }
                                $data['type'] = 'success';
                            }
                        } else {
                            $data['type'] = 'success';
                        }

                    } // IF UNLIKE
                    else {
                        $liked = Seriesmedialikedislike::ofUser($userid)->likes()->where('seriesmediaid', $seriesmediaid)->get();
                        if ($liked->isEmpty()) {
                            $data['type'] = 'success';
                        } else {
                            $seriesmedialikedislike = $liked->first();
                            $allok = true;
                            $notifications = Notification::oflike()->where('contentid', $seriesmedialikedislike->seriesmedialikesdislikeid)->get();
                            if (!$notifications->isEmpty()) {
                                $notification = $notifications->first();
                                if (!$notification->delete()) {
                                    $allok = false;
                                }
                            }

                            $postlikefollowers = Notification::where(['type' => '6', 'contentid' => $seriesmedialikedislike->seriesmedialikesdislikeid])->get();
                            foreach ($postlikefollowers as $postlikefollower) {
                                $f_id = $postlikefollower->notificationid;
                                Notification::where('notificationid', $f_id)->delete();
                            }

                            if ($allok) {
                                if ($seriesmedialikedislike->delete()) {
                                    $data['type'] = 'success';
                                }
                            }
                        }
                    }

                    if ($data['type'] == 'success') {
                        $count = intval($seriesmedia->series->user->likeinfluencer) + intval(Seriesmedialikedislike::likes()->where('seriesmediaid', $seriesmediaid)->count());

                        $admin_likes = Seriesmediaadminlikedislike::where('seriesmediaid', $seriesmediaid)->get();

                        $seriesmedia['alikes'] = 0;
                        if (!empty($admin_likes)) {
                            foreach ($admin_likes as $alike) {
                                if (!empty($alike)) {
                                    $seriesmedia['alikes'] = ($alike->no_of_likes) ? $alike->no_of_likes : 0;
                                }
                            }
                        }
                        $count = $count + $seriesmedia['alikes'];
                        $data['count'] = $count ;
                    }

                }

            }

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function adminlike(Request $request)
    {

        // AJAX REQUEST
        if (1) {

            $data = [];
            $data['type'] = 'error';
            $data['count'] = 0;

            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid' => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if (!$validator->fails()) {

                $seriesmediaid = intval($request->seriesmediaid);
                $likevalue = intval($request->likevalue);
                $userid = $this->globaldata['user']->userid;
                $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

                if (!empty($seriesmedia)) {

                    // IF LIKE
                    $liked = Seriesmediaadminlikedislike::where('seriesmediaid', $seriesmediaid)->get();

                    if ($liked->isEmpty()) {

                        $seriesmedialikedislike = new Seriesmediaadminlikedislike();
                        $seriesmedialikedislike->seriesmediaid = $seriesmediaid;
                        $seriesmedialikedislike->userid = $userid;
                        $seriesmedialikedislike->no_of_likes = $likevalue;
                        if ($seriesmedialikedislike->save()) {
                            if ($seriesmedia->series->userid != $userid) {
                                $data['type'] = 'success';
                            }
                        } else {
                            $data['type'] = 'success';
                        }
                        $data['count'] = $likevalue;
                    } else {
                        if (!empty($liked)) {
                            foreach ($liked as $likekey => $like_value) {
                                $likes = $like_value->no_of_likes;
                                $seriesmediaid = $like_value->seriesmediaid;
                                $seriesmedialikedislikeid = $like_value->seriesmediaadminlikesdislikeid;
                            }
                        }
                        $seriesmedialikedislike = Seriesmediaadminlikedislike::find($seriesmedialikedislikeid);
                        $seriesmedialikedislike->no_of_likes = $likes + $likevalue;
                        $seriesmedialikedislike->save();
                        $data['type'] = 'success';
                        $data['count'] = $likes + $likevalue;
                    }
                    // IF UNLIKE

//                    if ($data['type'] == 'success') {
//                        $count = intval(Seriesmedialikedislike::where('seriesmediaid', $seriesmediaid)->get());
//                        $data['count'] = $count;
//                    }

                }

            }

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }


    public function requestCounter(Request $request)
    {
        // AJAX REQUEST
        if ($request->ajax()) {
            $data = [];
            $data['type'] = 'error';

            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid' => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS

            //getCounter
            $seriesmedia = Seriesmedia::find($request->seriesmediaid);
            $Original_count = $seriesmedia['request_counter'];
            $data['count'] = $Original_count;
            if (!$validator->fails()) {
                $loggedinuserid = $this->globaldata['user']->userid;
                $seriesmediaid = intval($request->seriesmediaid);
                $seriesmedia = Seriesmedia::find($seriesmediaid);
                $seriesid = $seriesmedia['seriesid'];

                $series = Series::where('seriesid', $seriesid)->first();
                $userid = $series['userid'];

                $data['loggedinuserid'] = $loggedinuserid;
                $data['userid'] = $userid;

                $counter_request = Requestmedia::where('seriesmediaid', $seriesmediaid)->where('userid', $loggedinuserid)->count();

                if ($counter_request <= 0) {
                    $request_media = new Requestmedia;
                    $request_media->seriesmediaid = $seriesmediaid;
                    $request_media->userid = $loggedinuserid;
                    if ($request_media->save()) {
                        $seriesmedia->request_counter = $seriesmedia->request_counter + 1;
                        $seriesmedia->save();
                        $seriesmedia = Seriesmedia::find($seriesmediaid);
                        $count = $seriesmedia['request_counter'];
                        if ($count % 5 == 0 || $count == 1) {
                            $notification = new Notification();
                            $notification->type = 5;
                            $notification->userid = $userid;
                            $notification->contentid = $seriesmedia['seriesmediaid'];
                            $notification->content_text = $count;
                            $notification->save();


                            // Push notification
                            $request_post_msg = '';
                            if ($count == 1) {
                                $request_post_msg = $count . " person wants to see your video";
                            } else {
                                $request_post_msg = $count . " people wants to see your video";
                            }
                            $title = 'Potatoes';
                            $msgPayload = array(
                                'mtitle' => $title,
                                'mdesc' => $request_post_msg,
                            );

                            $PNO = new PushNotification();
                            $request_post_token = User::find($userid);
                            $request_post_registration_id = $request_post_token['registration_id'];
                            $request_post_access_token = $request_post_token['access_token'];
                            $request_post_status = '';
                            if (!empty($request_post_access_token)) {
                                $request_post_status = $PNO->iOS($msgPayload, $request_post_access_token, $pathPerm);
                            }
                            if (!empty($request_post_registration_id)) {
                                $request_post_status = $PNO->android($msgPayload, $request_post_registration_id);
                            }

                            // $request_post_notifylog = new Notificationlog();
                            // $request_post_notifylog->title = $title;
                            // $request_post_notifylog->message = $request_post_msg;
                            // $request_post_notifylog->userid = $userid;
                            // $request_post_notifylog->status = $request_post_status;
                            // $request_post_notifylog->save();

                            $data['type'] = 'success';
                        }

                        // $data['username'] = $seriesmedia->series->user->fullname;
                        // $data['userid'] = $seriesmedia->series->userid;
                        $user_requesters = Followuser::where('userid', $seriesmedia->series->userid)->get();
                        foreach ($user_requesters as $user_requester) {
                            $notification1 = new Notification();
                            $notification1->type = 8;
                            $notification1->userid = $user_requester->followerid;
                            $notification1->request_post_name = $seriesmedia->series->user->fullname;
                            $notification1->content_text = "";
                            $notification1->contentid = $seriesmedia['seriesmediaid'];
                            $notification1->save();

                            $follower_request_post_msg = "Someone wants to see " . $seriesmedia->series->user->fullname . "'s post";
                            $title = 'Potatoes';
                            $PNO = new PushNotification();
                            $follower_request_post_msgPayload = array(
                                'mtitle' => $title,
                                'mdesc' => $follower_request_post_msg,
                            );

                            $follower_request_post_token = User::find($user_requester->followerid);
                            $follower_request_post_registration_id = $follower_request_post_token['registration_id'];
                            $follower_request_post_access_token = $follower_request_post_token['access_token'];
                            $follower_request_post_status = '';
                            if (!empty($follower_request_post_access_token)) {
                                $follower_request_post_status = $PNO->iOS($follower_request_post_msgPayload, $follower_request_post_access_token, $pathPerm);
                            }
                            if (!empty($follower_request_post_registration_id)) {
                                $follower_request_post_status = $PNO->android($follower_request_post_msgPayload, $follower_request_post_registration_id);
                            }

                            // $request_post_notifylog = new Notificationlog();
                            // $request_post_notifylog->title = $title;
                            // $request_post_notifylog->message = $follower_request_post_msg;
                            // $request_post_notifylog->userid = $user_requester->followerid;
                            // $request_post_notifylog->status = $follower_request_post_status;
                            // $request_post_notifylog->save();

                        }

                        $data['type'] = 'success';
                    }
                    if ($data['type'] == 'success') {
                        $seriesmedia = Seriesmedia::find($seriesmediaid);
                        $totalcount = $seriesmedia['request_counter'];
                        $data['count'] = $totalcount;
                        $data['caption'] = 'You successfully placed a request';
                    }
                } else {
                    $data['type'] = 'error';
                    $data['caption'] = 'You previously set a request';
                }
            }
            return response()->json($data);
        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }


    public function upload_days()
    {
        return view('upload_days');
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $data = [];

            $user = $this->globaldata['user'];
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid' => 'required',
                'reason' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if (!$validator->fails()) {
                $reason = $request->input('reason');
                $seriesmedia = Seriesmedia::find($request->input('seriesmediaid'));
                Mail::send('emails.reportcontent', ['seriesmedia' => $seriesmedia, 'user' => $user, 'reason' => $reason], function ($m) use ($seriesmedia, $user, $reason) {
                    $m->from(config('app.constants.admin_email'), 'Schemk');
                    $m->to(config('app.constants.admin_email'), 'Admin')->subject('A user report a content');
                });

                $data['type'] = 'success';
            } else {
                $data['type'] = 'error';
            }
            return response()->json($data);
        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function isviewed(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            $user = $this->globaldata['user'];
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'notificationid' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            // VALIDATION SUCCESS
            if (!$validator->fails()) {
                $notification = Notification::find($request->notificationid);
                $notification->is_viewed = 1;
                $notification->save();

                $data['type'] = 'success';
            } else {
                $data['type'] = 'error';
            }
            return response()->json($data);
        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function addnewupload(Request $request)
    {
        //  print_r($request);die();
        // $request->headers->set('Content-type text/html', 'charset=UTF-8');
        //   header('Content-type text/html; charset=UTF-8');
        // $data['seriesmediatitle']   = $request->seriesmediatitle;
        // return response()->json($data);
        // return response()->json($data);
        //   file_put_contents('debugging.txt', serialize($request),FILE_APPEND);
        if (1) {
            $data = [];
            $data['formData'] = $request->all();
            $loggedinuserid = $this->globaldata['user']->userid;
            $series = Series::ofuser($loggedinuserid)->get();
            if (!$series->isEmpty()) {

                $series = $series->first();
                // SERIES MEDIAS INSERT/UPDATE/DELETE
                // image file
                $seriesmediaimagefile = $request->file('seriesmediaimagefile');
                $camera_capture_image = $request->camera;
                // Video file
                $seriesmediavideofile = $request->file('seriesmediavideofile');
                $seriesmediavideofile_thumbnail = $request->file('seriesmediavideofile_thumbnail');
                $seriesmediatitle = $request->seriesmediatitle;
                $seriesmediaimmidiatepublish = $request->seriesmediaimmidiatepublish;
                $seriesmediadate = intval($request->seriesmediadate);
                $seriesmediamonth = intval($request->seriesmediamonth);
                $seriesmediahour = intval($request->seriesmediahour);
                $seriesmediaminute = intval($request->seriesmediaminute);
                $seriesmediatime = $request->seriesmediapublish_time;
                $seriesmediapublish_day = $request->seriesmediapublish_day;
                $newmediauploaded = false;
                $allok = true;
                $newseriesmedias = [];

                $camerafilename = '';
                $thumbnail_filename = '';

                 require_once(__DIR__ . '/../../../vendor/james-heinrich/getid3/getid3/getid3.php');
                $getID3 = new \getID3;
                if ($seriesmediavideofile) {
                    if ($seriesmediavideofile_thumbnail != '') {
                        $request_thumnailfile = $seriesmediavideofile_thumbnail;
                        $thumbnail_filename = rand(0000, 9999) . '-' . $request_thumnailfile->getClientOriginalName();
                        $request_thumnailfile->move('videothumb/', $thumbnail_filename);
                    }
                   
                    $ThisFileInfo = $getID3->analyze($seriesmediavideofile->getPathName());
        
                    $time = $ThisFileInfo['playtime_string'];
                    $dimension_x = $ThisFileInfo['video']['resolution_x'];
                    $dimension_y = $ThisFileInfo['video']['resolution_y'];

                    $split_time = explode(':', $time);
                    $modifier = pow(60, count($split_time) - 1);
                    $seconds = 0;
                    foreach ($split_time as $time_part) {
                        $seconds += ($time_part * $modifier);
                        $modifier /= 60;
                    }
                    if ($seconds > 180) {
                        $data['type'] = 'error';
//                        $data['caption'] = 'Your video is more than 60 seconds long';
                       $data['caption'] = 'Your video is more than 3 minutes long';

                        return response()->json($data);
                    }
                    $data['height'] = $dimension_x;
                    $data['width'] = $dimension_y;
                    // if($dimension_y < 700)
                    // {
                    //     $data['type'] = 'error';
                    //     $data['caption'] = 'Minimum video height is 720px';
                    //     return response()->json($data);
                    // }
                    // if($dimension_x < 1200)
                    // {
                    //     $data['type'] = 'error';
                    //     $data['caption'] = 'Minimum video width is 1200px';
                    //     return response()->json($data);
                    // }
                } else {
                    if (empty($camera_capture_image)) {
                        $requestimagefile = $seriesmediaimagefile;
                        $camerafilename = rand(0000, 9999) . '.' . $requestimagefile->getClientOriginalName();
                        $requestimagefile->move('uploads/', $camerafilename);
                    } else {
                        $encoded_data = $camera_capture_image;
                        $binary_data = base64_decode($encoded_data);
                        $cameraname = date('YmdHis');
                        $camerafilename = $cameraname . ".jpg";
                        $imagefile = file_put_contents("uploads/" . $camerafilename, $binary_data);
                    }
                }


                $seriesmedia = new Seriesmedia();
                $seriesmedia->title = $seriesmediatitle;
                $seriesmedia->isfile = 1;
                $seriesmedia->status = 1;
                $seriesmedia->filethumbname = $thumbnail_filename ? $thumbnail_filename : '';
                $seriesmedia->imagefile = $camerafilename ? $camerafilename : '';
                $seriesmedia->immidiatepublish = $seriesmediaimmidiatepublish;
                $seriesmedia->publish_day = $seriesmediapublish_day;
                // $seriesmedia->publish_time = $seriesmediahour. ':' .$seriesmediaminute;
                $seriesmedia->publish_time = $seriesmediatime;

                $user = new User();
                $user = User::find($loggedinuserid);
                $user->publish_day = $seriesmediapublish_day;
                // $user->publish_time        = $seriesmediahour. ':' .$seriesmediaminute;
                $user->publish_time = $seriesmediatime;
                $result = $user->update();

                if ($seriesmedia->immidiatepublish == 0) {

                    $nextDate = new DateTime();

                    $nextDate->modify('next ' . $seriesmediapublish_day);

                    $todayDate = new DateTime();
                    $diff = date_diff($nextDate, $todayDate);

                    if ($diff->format("%a") == 6) {

                        $seriesmedia->publishdate = $todayDate->format('Y-m-d') . ' ' . $seriesmediahour . ':' . $seriesmediaminute . ':00';

                    } else {

                        $seriesmedia->publishdate = $nextDate->format('Y-m-d') . ' ' . $seriesmediahour . ':' . $seriesmediaminute . ':00';
                    }
                }
                // SERIES MEDIA INSERT/UPDATE SUCCESS
                if ($series->seriesmedias()->save($seriesmedia)) {

                    $newseriesmedias[] = $seriesmedia;
                    if ($seriesmediavideofile) {
                        $file = $seriesmediavideofile;
                        $seriesmediaid = 0;
                        $uploadresponse = $this->uploadVideo($seriesmedia, $file, $seriesmediaid);

                        if (!$uploadresponse['result']) {
                            $allok = false;
                            $captionerror = $uploadresponse['message'];
                        }
                    }

                }

                if ($allok) {

                    // NOTIFY FOLLOWERS (IF ANY) ABOUT NEW MEDIA UPLOADED
                    $followusers = $this->globaldata['user']->followers()->get();
                    $this->notifyFollowersForNewMedia($series, $followusers, $newseriesmedias);
                    $data['redirectUrl'] = url('/series');
                    $data['type'] = 'success';
                    $data['caption'] = 'Air successfully added';

                } else {

                    $data['type'] = 'error';
                    $data['caption'] = isset($captionerror) ? $captionerror : 'Unable to upload your video at this moment. Kindly try again.';

                }


            } // INVALID SERIES
            else {

                $data['type'] = 'error';
                $data['caption'] = 'Invalid series.';

            }

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }
}