<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\Emailtemplate;
use App\User;
use App\Followuser;
use App\Activitytoken;
use App\Series;

class SignupController extends FrontbaseController {

	public function __construct() {
        parent::__construct();
        $this->middleware('userguest');
    }

	public function index() {
		$data = [];
		$data['menu_signup'] = true;
        $data['interests'] = config('app.constants.interests');
        sort($data['interests']);
        return view('signup', $data);
	}

    public function submit(Request $request){

    	// AJAX REQUEST
        if ($request->ajax()) {
                        
            $data = [];
            $email  = trim($request->email);
            $minimal  = intval($request->minimal);
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                    'firstname'     => 'required',
                    'email'         => 'required|email'
            ];
            if($minimal == 1) {
                $rules['password'] = 'required';
            }
            else {
                $rules['password'] = 'required|confirmed';
                $rules['imagefile'] = 'mimes:jpg,jpeg,png';
                // $rules['age'] = 'required|numeric';
            }
            
            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);
            $validator->after(function($validator) use ($email) {
                $res = User::where('email', $email)->get();
                if(count($res) > 0) {
                    $validator->errors()->add('email', 'Email is already in use. Please try different email.');
                }
            });
            
            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $user = new User();


                 
                $user->firstname    = trim($request->firstname);
                $user->email        = $email;
                $user->phoneno      = trim($request->phoneno);
                $user->usertype     = 2;
                $user->issuperadmin = 0;
                $user->status       = 1;
                $password           = $request->password;
                $user->password     = Hash::make($password);
                if($request->age){
                    $user->age          = intval($request->age);
                }
                $interests          = Input::get('interests');

                if (!empty($interests)) {
                    foreach($interests as $interest) {
                        $interestvalue[] = $interest;
                    }
                    $user->interests = implode(", ",$interestvalue);
                }
                $result = $user->save();
                $captionerror = 'Unable to register you at this moment. Kindly try after some time.';
            
                // USER INSERT SUCCESS
                if($result) {

                    // INSERT A DEFAULT SERIES FOR REGISTERED USER
                    $series = new Series();
                    $series->title              = 'Series ' . $user->fullname;
                    $series->slug               = $this->checkSlug(str_slug($series->title));
                    $series->isongoing          = 0;
                    $series->status             = 1;
                    $series->userid             = $user->userid;
                    $defaultseriesaddresult = $series->save();

                    $defaultInfluencers = User::where('influencer',1)->get();
                    foreach ($defaultInfluencers as $influencer) {
                        $followers = new Followuser;
                        $followers->followerid = $user->userid ;
                        $followers->userid =   $influencer->userid;
                        $defualtFollower = $followers->save();
                    }

                    // DEFAULT SERIES INSERT SUCCESS
                    if($defaultseriesaddresult) {

                        // GENERATE ACTIVATION TOKEN
                        $token = $user->newtoken;
                        $activitytoken = new Activitytoken();
                        $activitytoken->token = $token;
                        $activitytoken->type = 1;
                        $tokengenerated = $user->activitytokens()->save($activitytoken);

                        // TOKEN GENERATION SUCCESS
                        if($tokengenerated) {

                            // SEND REGISTRATION MAIL TO USER
                            $sendmailresult = false;
                            $emailtemplateid = config('app.constants.emailtemplates.newuserregistraion');
                            $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();
                            if(!$emailtemplate->isEmpty()) {
                                $emailtemplate = $emailtemplate->first();
                                $sendmailresult = sendRegistrationMailToUser($user, $emailtemplate, $token);
                            }

                            // SEND REGISTRATION MAIL SUCCESS
                            if($sendmailresult) {
                                                            
                                // UPLOAD USER IMAGE IF SELECTED
                               // $imageuploadresult = uploadUserImage($user, $request);
							   if($request->hasFile('imagefile')) {
					
							   $avatar = $request->file('imagefile');
								$filename = time() . '.' . $avatar->getClientOriginalExtension();
								/* echo $filename;
								exit(); */
								$avatar->move('uploads/', $filename);

								$user->imagefile = $filename;
								$user->save();
							   }
							   $data['type'] = 'success';

                            }                        
                            // SEND REGISTRATION MAIL FAIL
                            else {

                                $user->delete();
                                $series->delete();
                                $data['type'] = 'error';
                                $data['caption'] = $captionerror;

                            }

                        }
                        // TOKEN GENERATION FAIL
                        else {
                            
                            $user->delete();
                            $series->delete();
                            $data['type'] = 'error';
                            $data['caption'] = $captionerror;

                        }

                    }
                    // DEFAULT SERIES INSERT FAIL 
                    else {

                        $user->delete();
                        $data['type'] = 'error';
                        $data['caption'] = $captionerror;

                    }

                }
                // USER INSERT FAIL
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

    public function verify($token) {

        $data = [];
        $data['type'] = 'error';
        $data['message'] = 'Verification link expired or doesn\'t exist.';

        $token = base64_decode($token);
        $activitytoken = Activitytoken::verifyregistration()->where('token', $token)->get();

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
                    //$data['message'] = 'Thank you for verifying your email. You now can login to your account.';
                    $data['message'] = 'Your Account has been confirmed!';

                }
                // USER NOT VERIFIED
                else {
                    $data['message'] = 'Unable to verify your account at this moment. Please try refreshing the page.';
                }

            }

        }

        return view('userverification', $data);

    }

}
