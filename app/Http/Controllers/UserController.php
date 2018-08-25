<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use App\Http\Controllers\SerieslistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\PushNotification;
use App\Notificationlog;
use App\Emailtemplate;
use App\User;
use App\Followuser;
use App\Activitytoken;
use App\Notification;
use App\Series;
use App\UserReport;
use App\Seriesmedia;
use App\Hashtag;
use App\Truefriend;
use Carbon\Carbon;

class UserController extends FrontbaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware('userauth', ['except' => ['emailverify', 'view']]);
    }
    
    public function index() {
        $data = [];
        $data['menu_profile'] = true;
        $data['user'] = $this->globaldata['user'];
        $interests = $this->globaldata['user']->interests;

        $data['interestsarray'] = explode(", ",$interests);
        
        $diff_array = array_diff($data['interestsarray'], config('app.constants.interests'));
        if (array_filter($diff_array)) {
            $data['custome_array'] = array_merge(config('app.constants.interests'), $diff_array);        
        }
        else{
            $data['custome_array'] = config('app.constants.interests');
        }
        sort($data['custome_array']);
       
        return view('profile', $data);
    }

    public function tour() {
	$user=User::find(Input::get('uid'));
	$user->tour_count=$user->tour_count+1;
	$user->update();
	
	}
    public function view($id) {
        $user = User::find($id);
        if(!empty($user)) {
            $data = [];
            $data['user'] = $user;
            $loggedinuserid = 0;
            if(isset($this->globaldata['user'])) {
                $loggedinuserid = $this->globaldata['user']->userid;
            }
             $data['followuser'] = Followuser::where('followerid', $loggedinuserid)->where('userid',$id)->get();
           // $data['followuser'] = Followuser::where('followerid', $id)->where('userid',$loggedinuserid)->get();
            $data['followusercount'] = intval($user->followerinfluencer) + intval($user->followers()->count());
            $seriesmedias = [];
            $series = Series::ofuser($id)->get();

            if(!$series->isEmpty()) {
                $series = $series->first();
                $seriesmediasresult = $series->seriesmedias()->active()->orderBy('seriesmediaid', 'desc')->get();
                foreach($seriesmediasresult as $seriesmedia) {
                    if($seriesmedia->isprocessing == 1 && $seriesmedia->videoid != '') {
                        $seriesmedia->updateProcessingStatus();
                        if($seriesmedia->isprocessing == 0 && $seriesmedia->status == 1) {
                            $seriesmedias[] = $seriesmedia;
                        }
                    }
                    else {
                        $seriesmedias[] = $seriesmedia;
                    }
                }
            }

            $data['seriesmedias'] = $seriesmedias;
 $data['truefc'] = count(Truefriend::where('friend1_id',$id)->orWhere('friend2_id',  $id)->get());
      
            return view('viewprofile', $data);
        }
        else {
            return view('errors.404');
        }
    }

    public function myfollower() {
        $data = [];
        $data['menu_myfollower'] = true;
        $data['myfollowusers'] = Followuser::where('userid', $this->globaldata['user']->userid)->get();
		$data['truefc'] = Truefriend::where('friend1_id',$this->globaldata['user']->userid)->orWhere('friend2_id',  $this->globaldata['user']->userid)->get();
        // return $data['myfollowusers'];exit;
		$tfud=array();
		foreach($data['truefc'] as $t){
			if($t->friend1_id!=$this->globaldata['user']->userid){
			$tfud[$t->friend1_id] = User::find($t->friend1_id);	
			}
			if($t->friend2_id!=$this->globaldata['user']->userid){
				$tfud[$t->friend2_id] = User::find($t->friend2_id);	
			}
		}
		$data['tfud']=$tfud;
        return view('myfollower', $data);
    }
    
    public function report(Request $request){
        if ($request->ajax()) {
            $data = [];

            $user = $this->globaldata['user'];
            $userid = $user->userid;
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'userid_to_report'     => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {
                $userReport = new UserReport;
                $userReport->userid = $userid;
                $userReport->report_userid = $request->input('userid_to_report');
                $userReport->save();

                $reported_user = User::find($request->input('userid_to_report'));
                $admin_email = config('app.constants.admin_email');

                Mail::send('emails.reportuser', ['user'=>$user, 'reported_user' => $reported_user], function ($m) use ($admin_email) {
                    $m->from($admin_email, 'Schemk');
                    $m->to($admin_email, 'Admin')->subject('An user wanto to report another user');
                });

                $data['type'] = 'success';        
            }else{
                $data['type'] = 'error';
            }
            return response()->json($data);
        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function submit(Request $request){

        // AJAX REQUEST
        if ($request->ajax()) {
                        
            $data = [];

            $user = $this->globaldata['user'];
            $email  = trim($request->email);
            $userid = $user->userid;
            $password_changed = false;
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                    'firstname'     => 'required',
                    'email'         => 'required|email',
                    'password'      => 'confirmed',
                    'imagefile'     => 'mimes:jpg,jpeg,png',
                    'age'       => 'required|numeric'
            ];
            
            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);
            $validator->after(function($validator) use ($email, $userid) {
                $res = User::where('userid', '!=', $userid)->where('email', $email)->get();
                if(count($res) > 0) {
                    $validator->errors()->add('email', 'Email is already in use. Please try different email.');
                }
            });
            
            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $olduser = $user;
                 
                $oldemail           = $user->email;
                $user->firstname    = trim($request->firstname);
				$user->lastname    = trim($request->lastname);
                $user->email        = $email;
                $user->age          = intval($request->age);
                 $user->bod          = $request->bod;
                $user->aboutme      = trim($request->aboutme);

                $password           = $request->password;
                if($password != '') {
                    $user->password     = Hash::make($password);
					$password_changed = true;
                }

                $emailchanged = false;
                $changed = $user->isDirty() ? $user->getDirty() : false;
                if($changed !== false && isset($changed['email'])) {
                    $user->status = 0;
                    $emailchanged = true;
                }
                
                $interests  = Input::get('interests');
                if (!empty($interests)) {
                    foreach($interests as $interest) {
                        $interestvalue[] = $interest;
                    }
                    $user->interests = implode(", ",$interestvalue);
                }
                else {
                    $user->interests = '';
                }

                $result = $user->save();
                $captionerror = 'Unable to update your profile at this moment. Kindly try after some time.';
            
                // USER UPDATE SUCCESS
                if($result) {

                    // DELETE IMAGE IF SET TO TRUE
                    if(intval($request->deleteimage) == 1) {

                        $imgpath = public_path($user->userdir);
                        // DELETE OLD IMAGE FILE IF ANY
                        if($user->hasimage) {
                            File::deleteDirectory($imgpath);
                        }                   

                        $user->imagefile = '';
                        $user->update();
                    }

                    // UPLOAD USER IMAGE IF SELECTED
                  //  $imageuploadresult = uploadUserImage($user, $request);
				//  echo "update====";
				  if($request->hasFile('imagefile')) {
						$avatar = $request->file('imagefile');
						$filename = time() . '.' . $avatar->getClientOriginalExtension();
						/* echo $filename;
						exit(); */
						$avatar->move('uploads/', $filename);

						$user->imagefile = $filename;
						$user->save();
				  }

                    // EMAIL ADDRESS CHANGED 
                    // SO GENERATE EMAIL VARIFICATION TOKEN AND SEND MAIL
                    if($emailchanged) {
                        
                        // GENERATE ACTIVATION TOKEN
                        $token = $user->newtoken;
                        $activitytoken = new Activitytoken();
                        $activitytoken->token = $token;
                        $activitytoken->type = 2;
                        $tokengenerated = $user->activitytokens()->save($activitytoken);

                        // TOKEN GENERATION SUCCESS
                        if($tokengenerated) {

                            // SEND REGISTRATION MAIL TO USER
                            $sendmailresult = false;
                            $emailtemplateid = config('app.constants.emailtemplates.useremailverification');
                            $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();
                            if(!$emailtemplate->isEmpty()) {
                                $emailtemplate = $emailtemplate->first();
                                $sendmailresult = sendEmailVerificationMailToUser($user, $emailtemplate, $token);
                            }
                            
                            // SEND EMAIL ACTIVATION MAIL SUCCESS
                            if($sendmailresult) {

                                $data['type'] = 'success';

                            }
                            // SEND EMAIL ACTIVATION MAIL FAIL
                            else {

                                $data['type'] = 'error';                                

                            }

                        }
                        // TOKEN GENERATION FAIL
                        else {

                            $data['type'] = 'error';

                        }

                    }
                    // EMAIL ADDRESS NOT CHANGED
                    else {

                        $data['type'] = 'success';

                    }

                    if($data['type'] == 'success') {

                        $data['caption'] = 'Your profile updated successfully.';
						if($password_changed)
						{
							$data['redirectUrl'] = url('/user/logout');
						}
                        else if($emailchanged) {
                            $data['redirectUrl'] = url('/user/logout');
                        }
                        else {
                            $data['redirectUrl'] = url('/user/edit');
                        }

                    }
                    else {

                        $data['caption'] = $captionerror;
                        $user->email = $oldemail;
                        $user->status = 1;
                        $user->update();

                    }

                }
                // USER UPDATE FAIL
                else {

                    $data['type'] = 'error';
                    $data['caption'] = $captionerror;

                }

            }
            // VALIDATION FAIL
            else {

                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();
                $data['errormessage'] = $validator->errors()->all()[0];

            }
                    
            return response()->json($data);
        
        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function emailverify($token) {
        
        $data = [];
        $data['type'] = 'error';
        $data['message'] = 'Verification link expired or doesn\'t exist.';

        $token = base64_decode($token);
        $activitytoken = Activitytoken::verifyemail()->where('token', $token)->get();

        // VALID TOKEN
        if(!$activitytoken->isEmpty()) {
            
            // GET THE TOKEN RECORD FROM DATABASE
            $activitytoken = $activitytoken->first();
            $user = $activitytoken->user()->frontuser()->get();

            // USER ASSOCIATED WITH THIS TOKEN FOUND 
            if(!$user->isEmpty()) {
                
                $user = $user->first();
                $user->status = 1;

                // USER VERIFIED
                if($user->update()) {

                    $activitytoken->delete();
                    $data['type'] = 'success';
                    $data['message'] = 'Thank you for verifying your email. You now can login to your account.';

                }
                // USER NOT VERIFIED
                else {
                    $data['message'] = 'Unable to verify your email at this moment. Please try refreshing the page.';
                }

            }

        }

        return view('emailverification', $data);

    }

    public function logout(){
        Auth::user()->logout();
        return redirect('/user/signin');
    }
 public function trequestCheck(Request $request) {
	 $c_user=$this->globaldata['user']->userid;
	 $req=Truefriend::where('request_status',0)->where('friend2_id',  $c_user)->get();
	 $udata='';
	 if(count($req)>0){
		$udata = User::find($req[0]['friend1_id']);
		//$udata=Users::where('userid',$req[0]['friend1_id'])->get();
return response()->json($ret=array('success'=>true,'user'=>$udata,'tr'=>$req[0]));		
	 }else{
	return response()->json($ret=array('success'=>false,'uid'=>$c_user));	 
	 }
 }
 public function trequest(Request $request) {
	 
	 if(Input::get('f1')!=null && Input::get('f2')!=null){ 
	 
	 $tfc = Truefriend::where('friend1_id',Input::get('f1'))->Where('friend2_id',  Input::get('f2'))->get();
	 $tfc1 = Truefriend::where('friend1_id',Input::get('f2'))->Where('friend2_id',  Input::get('f1'))->get();
	 $truefc1 = count(Truefriend::where('friend1_id',Input::get('f1'))->orWhere('friend2_id',  Input::get('f1'))->get());
	 $truefc2 = count(Truefriend::where('friend1_id',Input::get('f2'))->orWhere('friend2_id',  Input::get('f2'))->get());
	 if(count($tfc)==0 && count($tfc1)==0){
		 if($truefc1<25 && $truefc2<25){
		                    $tf = new Truefriend();
                            $tf->friend1_id =Input::get('f1') ;
                            $tf->friend2_id =Input::get('f2') ;
                            $tf->request_status = 0 ;                       
                            $tf->request_date = date('Y-m-d H:i:s',time());                       
                            if($tf->save()) {
								$ret=array('success'=>true,'msg'=>"Your Truefriend request has been sent");
return response()->json($ret);
							}else{
								$ret=array('success'=>false,'msg'=>"Request get error");
return response()->json($ret);
							}
		 }else{
			$ret=array('success'=>false,'msg'=>"You or your friend reached maximum Truefriend");
						return response()->json($ret); 
		 }
							}else{
								$ret=array('success'=>false,'msg'=>"You are already Truefriend");
						return response()->json($ret);		
							}
	 }else{
	 return response()->json($ret=array('success'=>false,'msg'=>"Request get error"));
	 }
	}
	 public function trequestUpdate(Request $request) {
		 if(Input::get('id')!=null){
		 $tfr = Truefriend::find(Input::get('id'));
		 if(Input::get('status')==1){
			$tfr->request_status = 1; 
			$tfr->request_accept_date = date('Y-m-d H:i:s',time());
			$tfr->update();
			
		 }else{
			 $tfr->delete();
		 }
		 }
		 return response()->json($ret=array('success'=>true,'msg'=>"Request Updated"));
	}
    public function follow(Request $request) {
        
        // print_r($request);die();
        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $data['type'] = 'error';
            $data['count'] = 0;
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'userid'   => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $userid = intval($request->userid);
                $followvalue = intval($request->followvalue);
                $followerid = $this->globaldata['user']->userid;
                $user = User::find($userid);

                if(!empty($user)) {

                    // IF FOLLOW
                    if($followvalue == 1) {

                        $followed = Followuser::where('followerid',$followerid)->where('userid',  $userid)->get();

                        if($followed->isEmpty()) {
                            $followuser = new Followuser();
                            $followuser->followerid =$followerid ;
                            $followuser->userid = $userid ;                       
                            if($followuser->save()) {

                                $notification = new Notification();
                                $notification->type = 3;
                                $notification->userid = $userid;
                                $notification->contentid = $followuser->followuserid;
                                $notification->save();

                                // Push notification
                                $title = 'Potatoes';
                                $follow_msg = $this->globaldata['user']->fullname." is now following";
                                 $msgPayload = array(
                                    'mtitle'    => $title,
                                    'mdesc'     => $follow_msg,
                                );

                                $PNO = new PushNotification();
                                $follow_user_token = User::find($userid);
                                $follow_user_registration_id = $follow_user_token['registration_id'];
                                $follow_user_access_token = $follow_user_token['access_token'];
                                $follow_user_status = '';
                                if(!empty($follow_user_access_token))
                                {
                                    $follow_user_status = $PNO->iOS($msgPayload,$follow_user_access_token,$pathPerm);
                                }
                                if(!empty($follow_user_registration_id))
                                {
                                    $follow_user_status = $PNO->android($msgPayload,$follow_user_registration_id);
                                }

                                // $follow_notifylog = new Notificationlog();
                                // $follow_notifylog->title = $title;
                                // $follow_notifylog->message = $follow_msg;
                                // $follow_notifylog->userid = $userid;
                                // $follow_notifylog->status = $follow_user_status;
                                // $follow_notifylog->save();

                                $data['type'] = 'success';
                            }
                        }
                        else {
                            $data['type'] = 'success';
                        }

                    }
                    // IF UNFOLLOW
                    else {
               $followed = Followuser::where('followerid', $followerid)->where('userid',$userid )->get();
                       
                        if($followed->isEmpty()) {
                            $data['type'] = 'success';
                        }
                        else {
                            $followuser = $followed->first();
                            $allok = true;
                           // $notifications = Notification::offollow()->where('contentid', $followuser->id)->get();
                            $notifications = Notification::offollow()->where('contentid', $followuser->followuserid)->get();
                            if(!$notifications->isEmpty()) {
                                $notification = $notifications->first();
                                if(!$notification->delete()) {
                                    $allok = false;
                                }
                            }
                            if($allok) {
                                if($followuser->delete()) {
                                    $data['type'] = 'success';
                                }
                            }
                        }
                    }

                    if($data['type'] == 'success') {
                        $count = intval($user->followerinfluencer) + intval($user->followers()->count());
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
	
	// Search user
	public function search(Request $request) {
	   $search_tag =  $request->search_tag;
       $keyword = $request->seriessearch;
        $data = [];
       if($search_tag == 'hashtag')
       {
            $seriesmedias = [];
            $tag = '';
            if (strpos($keyword, '#') !== false) {
                    $tag = $keyword;
                }
                else
                {
                    $tag = "#".$keyword;
                }
            $seriesmediasresult = Seriesmedia::where('title','LIKE',"%".$tag."%")->paginate('4');
            if(!$seriesmediasresult->isEmpty()) {
                $hashtags = Hashtag::where('hashtag_content','=',$tag)->first();
                $hashtagid = $hashtags['hashtag_id'];
                $counter = $hashtags['counter'];
                if(empty($hashtags))
                {
                    $hashtag = new Hashtag;
                    $hashtag->hashtag_content = $tag;
                    $hashtag->counter = 1;
                    $hashtag->save();
                }
                else
                {   
                    $hashtag = Hashtag::find($hashtagid);
                    $hashtag->counter = $counter+1;
                    $hashtag->save();
                }
                foreach ($seriesmediasresult as $seriesmedia) {
                    $series = $seriesmedia->series;
                    $userId = $series->userid;
                    $seriesmediaid = $seriesmedia->seriesmediaid;

                    $now = Carbon::now();
                    $episodes_will_air = Seriesmedia::active()->ofuser($userId)->where('publishdate', '>', $now)->get()->count();
                    $seriesmedia['episodes_will_air'] = ($episodes_will_air > 0) ? $episodes_will_air : 0;
                    $seriesmedias[] = $seriesmedia;
                }
                return view('searchtag', ['seriesmedias' => $seriesmedias,'keyword'=> $tag]);
            }
            else {
                $resuser = User::Where('firstname', 'LIKE', "%".$keyword."%")->get();
                // if(!$resuser->isEmpty()) {
                    $data['users'] =  $resuser;
                    return view('users', $data,['search_tag'=> $search_tag]);
                // }
            }
       }
       else
       {
            $resuser = User::Where('firstname', 'LIKE', "%".$keyword."%") ->get();
                $data['users'] =  $resuser;
                return view('users', $data);
       }
    }
 
    public function getAllUsers(){
        return view('allUsers');
        exit;
    } 


    public function getData(Request $request){
        
        if ($request->ajax()) {

            $data = [];
            $page = intval($request->page);
            $perpage = 10;
            $lastpage = 0;

            $resuser = User::active()->frontuser()->paginate($perpage);
	    $total = User::active()->frontuser()->get()->count();
	    
            $lastpage = $resuser->lastPage();
            $data['total_users'] = $total ;
            $data['htmldata'] = view('ajax.usersList', ['users' => $resuser])->render();
            $data['lastpage'] = $lastpage;
            // print_r($data);exit;
            return response()->json($data);
        }
    }

    public function searchResult($keyword = '')
    {
        $seriesmedias = [] ;
        if (strpos($keyword, '#') !== false) {
            $keyword = $keyword;
        }
        else
        {
            $keyword = "#".$keyword;
        }
        $seriesmediasresult = Seriesmedia::where('title','LIKE',"%".$keyword."%")->get();
        foreach ($seriesmediasresult as $seriesmedia) {
            $series = $seriesmedia->series;
            $userId = $series->userid;
            $seriesmediaid = $seriesmedia->seriesmediaid;

            $now = Carbon::now();
            $episodes_will_air = Seriesmedia::active()->ofuser($userId)->where('publishdate', '>', $now)->get()->count();
            $seriesmedia['episodes_will_air'] = ($episodes_will_air > 0) ? $episodes_will_air : 0;
            $seriesmedias[] = $seriesmedia;
        }

        return view('searchtag', ['seriesmedias' => $seriesmedias,'keyword'=> $keyword]);
    }


    //hashtag searching
    public function hash_tag($searchtag = ''){
        $perpage = 5;
        $seriesmedias = [];
        $users = User::where('influencer',1)->get();
        $f_users = [];
        foreach($users as $fuser) {
            $f_users[] = $fuser->userid;
        }
        $series = Series::select('seriesid')->active()->whereIn('userid',$f_users)->get();
        $s_users = [];
        foreach($series as $suser) {
            $s_users[] = $suser->seriesid;
        } 
        if(!$series->isEmpty()) {
            $series = $series->first();            
            $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->desc()->get(); 
        }
        if(isset($seriesmediasresult) && !$seriesmediasresult->isEmpty()) {
                foreach($seriesmediasresult as $seriesmedia) {
                    if($seriesmedia->ispublished){
                        if($seriesmedia->isprocessing == 1 && $seriesmedia->videoid != '') {
                            $seriesmedia->updateProcessingStatus();
                            if($seriesmedia->isprocessing == 0 && $seriesmedia->status == 1) {
                                $seriesmedias[] = $seriesmedia;
                            }
                        }
                        else {
                            $seriesmedias[] = $seriesmedia;
                        }
                    }
                }
            }
        if($searchtag == 'hashtag')
        {
            $searchResult = Hashtag::orderBy('hashtag_id', 'DESC')->paginate($perpage);
            $data = view('ajax.searchhashtag', ['hashtags' => $searchResult,'seriesmedias' => $seriesmedias])->render();
            return response()->json($data);
        }
        elseif($searchtag == 'person')
        {
            $searchResult = Followuser::where('userid', $this->globaldata['user']->userid)->paginate(5);
            $data = view('ajax.searchuser', ['hashtags' => $searchResult,'seriesmedias' => $seriesmedias])->render();
            return response()->json($data);
        }
        $searchResult = Hashtag::orderBy('hashtag_id', 'DESC')->paginate($perpage);
        return view('hashtag',['hashtags' => $searchResult,'seriesmedias' => $seriesmedias]);

    }
	// User notification
	
    public function user_notification() {
		
		return view('notifications');
		  
   
       // return view('users', $data);
    }
	
	public function schedule() {
		/* $data = [];
        $data['menu_myfollower'] = true;
        $data['myfollowusers'] = Followuser::where('userid', $this->globaldata['user']->userid)->get();
        return view('schedule', $data); */
		$data = [];
         $data['menu_myfollower'] = true;
        $followusers = Followuser::where('followerid', $this->globaldata['user']->userid)->get();
		
		$user_schedule = array();
		$day_array = array("Mondays","Tuesdays","Wednesdays","Thursdays","Fridays","Saturdays","Sundays");
		for($i=0;$i<count($day_array);$i++)
		{
			
			$s_user = [];
			foreach($followusers as $fuser) {
				//dd($fuser);
				//if($fuser->userid != null && $fuser->userid != ''){
				// echo $fuser->userid;
				// echo $fuser->publish_day;
				 $res = User::where('userid',  $fuser->userid)->get();
				 $first_user = $res->first();
				// echo $first_user->publish_day."===".$day_array[$i];
				
				if(isset($first_user->publish_day)){
				if($first_user->publish_day == $day_array[$i]) {
					$s_user[] =  $first_user;
				} 
				}
				//}
			}
			$user_schedule[$day_array[$i]] = $s_user;
					
		}
		/* echo "<pre>";
		print_r($user_schedule);
		echo "</pre>"; 
		exit(); */
        $data['page_title'] = 'schedule';
		$data['user_schedule'] = $user_schedule ;
		return view('schedule', $data); 
		
    }
		
	public function schedule_day($keyword) {
		/* $data = [];
        $data['menu_myfollower'] = true;
        $data['myfollowusers'] = Followuser::where('userid', $this->globaldata['user']->userid)->get();
        return view('schedule', $data); */
		$data = [];
         $data['menu_myfollower'] = true;
        $followusers = Followuser::where('followerid', $this->globaldata['user']->userid)->get();
		
		$user_schedule = array();
		$day_array = array($keyword);
		for($i=0;$i<count($day_array);$i++)
		{
			
			$s_user = [];
			foreach($followusers as $fuser) {
				// echo $fuser->userid;
				// echo $fuser->publish_day;
				 $res = User::where('userid',  $fuser->userid)->get();
				 $first_user = $res->first();
				// echo $first_user->publish_day."===".$day_array[$i];
				
				
				if($first_user->publish_day == $day_array[$i]) {
					$s_user[] =  $first_user;
				} 
				
			}
			$user_schedule = $s_user;
					
		}
		/* echo "<pre>";
		print_r($user_schedule);
		echo "</pre>"; 
		exit(); */
		$data['user_schedule'] = $user_schedule ;
		return view('schedule_day', $data); 
		
    }
}


