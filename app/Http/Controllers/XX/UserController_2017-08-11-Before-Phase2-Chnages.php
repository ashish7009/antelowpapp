<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\Emailtemplate;
use App\User;
use App\Followuser;
use App\Activitytoken;

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

    public function view($id) {
        $user = User::find($id);
        if(!empty($user)) {
            $data = [];
            $data['user'] = $user;
            $loggedinuserid = 0;
            if(isset($this->globaldata['user'])) {
                $loggedinuserid = $this->globaldata['user']->userid;
            }
            $data['followuser'] = Followuser::where('followerid', $loggedinuserid )->where('userid', $id)->get();

            $data['followusercount'] = Followuser::where('userid', $id)->count();
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
        return view('myfollower', $data);
    }
    

    public function submit(Request $request){

        // AJAX REQUEST
        if ($request->ajax()) {
                        
            $data = [];

            $user = $this->globaldata['user'];
            $email  = trim($request->email);
            $userid = $user->userid;
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                    'firstname'     => 'required',
                    'email'         => 'required|email',
                    'password'      => 'confirmed',
                    'state'         => 'required',
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
                $user->email        = $email;
                $user->phoneno      = trim($request->phoneno);
                $user->state        = trim($request->state);
                $user->city         = trim($request->city);
                $user->pincode      = trim($request->pincode);
                $user->age          = intval($request->age);
                $user->aboutme      = trim($request->aboutme);

                $password           = $request->password;
                if($password != '') {
                    $user->password     = Hash::make($password);
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
                    $imageuploadresult = uploadUserImage($user, $request);

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

                        if($emailchanged) {
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

    public function follow(Request $request) {

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
                $user = User::active()->find($userid);

                if(!empty($user)) {

                    // IF FOLLOW
                    if($followvalue == 1) {

                        $followed = Followuser::where('followerid', $followerid)->where('userid', $userid)->get();
                        if($followed->isEmpty()) {
                            $followuser = new Followuser();
                            $followuser->followerid = $followerid;
                            $followuser->userid = $userid;                       
                            if($followuser->save()) {
                                $data['type'] = 'success';
                            }
                        }
                        else {
                            $data['type'] = 'success';
                        }

                    }
                    // IF UNFOLLOW
                    else {
                        $followed = Followuser::where('followerid', $followerid)->where('userid', $userid)->get();
                        if($followed->isEmpty()) {
                            $data['type'] = 'success';
                        }
                        else {
                            $followuser = $followed->first();
                            if($followuser->delete()) {
                                $data['type'] = 'success';
                            }
                        }
                    }

                    if($data['type'] == 'success') {
                        $count = Followuser::where('userid', $userid)->count();
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


