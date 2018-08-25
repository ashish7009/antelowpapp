<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\User;
use App\Followuser;
use App\Activitytoken;
use App\Series;
use App\Emailtemplate;
use Laravel\Socialite\Facades\Socialite;



class SigninController extends FrontbaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware('userguest');
    }
    
    public function index() {
        $data = [];
        $data['menu_signin'] = true;
        $data['logotohome'] = true;
        return view('signin', $data);
    }

    public function submit(Request $request) {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                    'email'   => 'required|email',
                    'password'   => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $email = trim($request->email);
                $password = $request->password;
                $remember = 0;
                if($request->remember){
                    $remember = 1;
                }

                
                // LOGIN SUCCESS

                if(Auth::user()->attempt(['email' => $email,'password' => $password, 'status' => 1, 'usertype' => 2], $remember)) {

                    // $user_Details = User::where('email','=',$email)->first();
                    //Get Details from API
                    // $json = json_decode(file_get_contents(url('/user/signin/'.$user_Details->userid)), true);
                    // $data['json'] = $json;
                    // $fcm_token = $json['data']['registration_id'];
                    
                    // $data['fcm_token'] = $fcm_token;
                    
                    // $user = User::where('email','=', $email)->update(['registration_id' => $fcm_token]);


                    $data['type'] = 'success'; 
                    $data['redirectUrl'] = url('/series');
                }
                // LOGIN FAIL
                else {
                    
                    if(Auth::user()->attempt(['email' => $email,'password' => $password, 'usertype' => 2], $remember)){
                        
                        $usersList = User::where('email', "=", $email)->get();
                        
                        if(count($usersList) > 0) {
                            $data['data'] = 'not confirmed';
                            $signedInUser = $usersList->first();
                            
                            $createdAt = $signedInUser->created_at;
                            if($createdAt->diffInDays(Carbon::now()) > 7){
                                $data['type'] = 'success';
                                $data['redirectUrl'] = url('/series');
                            }
                            else{
                                Auth::user()->logout();
                                $data['type'] = 'error';
                                $data['caption'] = 'Please verify your email by clicking the registration link sent your mail';
                            } 
                        }
                        else{
                            Auth::user()->logout();
                            $data['type'] = 'error';
                            $data['caption'] = 'Please verify your email by clicking the registration link sent your mail';
                        }
                    }
                    else{
                        $data['type'] = 'error';
                        $data['caption'] = 'Invalid email address or password.';
                    }
                    
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

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
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

    public function handleFacebookCallback(Request $request)
    {
        $captionerror='';
        if (!$request->has('code') || $request->has('denied')) {
            return redirect('/');
        }
        
        try {
            $user = Socialite::driver('facebook')->user();
			

            $checkUser = User::where('email',$user['email'])->first();

            if(is_null($checkUser)){
                $checkUser = new User;
                $checkUser->firstname = $user->name;
                $checkUser->email = $user->email;
                $checkUser->password = Hash::make('Baf^%z323hGDda');
                $checkUser->save();

                // INSERT A DEFAULT SERIES FOR REGISTERED USER
                $series = new Series;
                $series->title              = 'Series ' . $checkUser->firstname;
                $series->slug               = $this->checkSlug(str_slug($series->title));
                $series->isongoing          = 0;
                $series->status             = 1;
                $series->userid             = $checkUser->userid;
                $defaultseriesaddresult = $series->save();

                $defaultInfluencers = User::where('influencer',1)->get();
                foreach ($defaultInfluencers as $influencer) {
                    $followers = new Followuser;
                    $followers->followerid = $checkUser->userid;
                    $followers->userid =   $influencer->userid;
                    $defualtFollower = $followers->save();
                }

                // DEFAULT SERIES INSERT SUCCESS
                if($defaultseriesaddresult) {

                    // GENERATE ACTIVATION TOKEN
                    $token = $checkUser->newtoken;
                    $activitytoken = new Activitytoken();
                    $activitytoken->token = $token;
                    $activitytoken->type = 1;
                    $tokengenerated = $checkUser->activitytokens()->save($activitytoken);
                    

                    // TOKEN GENERATION SUCCESS
                    if($tokengenerated) {

                        // SEND REGISTRATION MAIL TO USER
                        $sendmailresult = false;
                        $emailtemplateid = config('app.constants.emailtemplates.newuserregistraion');
                        $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();
                        if(!$emailtemplate->isEmpty()) {
                            $emailtemplate = $emailtemplate->first();
                            $sendmailresult = sendRegistrationMailToUser($checkUser, $emailtemplate, $token);
                        }

                        // SEND REGISTRATION MAIL SUCCESS
                        if($sendmailresult) {
                           $data['type'] = 'success';
                           $data['caption'] = "SEND REGISTRATION MAIL SUCCESS";
                        } else { // SEND REGISTRATION MAIL FAIL
                            $checkUser->delete();
                            $series->delete();
                            $data['type'] = 'error';
                            $data['caption'] = "SEND REGISTRATION MAIL FAIL";
                        }

                    }
                    // TOKEN GENERATION FAIL
                    else {
                        
                        $checkUser->delete();
                        $series->delete();
                        $data['type'] = 'error';
                        $data['caption'] = "TOKEN GENERATION FAIL";

                    }

                }
                // DEFAULT SERIES INSERT FAIL 
                else {

                    $checkUser->delete();
                    $data['type'] = 'error';
                    $data['caption'] = "DEFAULT SERIES INSERT FAIL ";

                }

            }
            // USER INSERT FAIL
            else {
                $data['type'] = 'error';
                $data['caption'] = "USER NOT FOUND";

            }
            // print_r($data);exit;
            
            Auth::user()->loginUsingId($checkUser->userid,true);
            
            if(Auth::check() && $data['type'] == 'success'){
            
                return redirect('/series');
            }else{
                return redirect('/');
            }
        } catch (Exception $e) {
            return redirect('/');
        }
    } 
}