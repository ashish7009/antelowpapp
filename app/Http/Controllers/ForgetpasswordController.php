<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Emailtemplate;
use App\User;
use App\Activitytoken;

class ForgetpasswordController extends FrontbaseController {

	public function __construct() {
        parent::__construct();
        $this->middleware('userguest');
    }

    public function index() {
        return view('forgotpassword');
    }

    public function submit(Request $request) {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];

            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
				'email'   => 'required|email'
            ];

            $email = trim($request->email);

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            $validator->after(function($validator) use ($email) {
                $res = User::where('email', $email)->frontuser()->get();
                if(count($res) == 0) {
                    $validator->errors()->add('email', 'No such email found, please verify the email entered.');
                }
            });

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $user = User::where('email', $email)->frontuser()->first();

                // GENERATE ACTIVATION TOKEN
                $token = $user->newtoken;
                $activitytoken = new Activitytoken();
                $activitytoken->token = $token;
                $activitytoken->type = 3;
                $tokengenerated = $user->activitytokens()->save($activitytoken);

                // TOKEN GENERATION SUCCESS
                if($tokengenerated) {

                    // SEND PASSWORD RESET MAIL TO USER
                    $sendmailresult = false;
                    $emailtemplateid = config('app.constants.emailtemplates.userresetpassword');
                    $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();
                    if(!$emailtemplate->isEmpty()) {
                        $emailtemplate = $emailtemplate->first();
                        $sendmailresult = sendResetPasswordMailToUser($user, $emailtemplate, $token);
                    }

                    // SEND PASSWORD RESET MAIL SUCCESS
                    if($sendmailresult) {
                        $data['type'] = 'success';
                    }

                }

            }
            // VALIDATION FAIL
            else {

                $errors = $validator->errors()->all();
                $data['type'] = 'error';
                $data['caption'] = $errors[0];
                $data['errorfields'] = $validator->errors()->keys();

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function resetpassword($token) {
    	$data = [];
        $data['type'] = 'error';
        $data['message'] = 'Pasword reset link expired or doesn\'t exist.';
        $activitytoken = Activitytoken::resetpassword()->where('token', base64_decode($token))->get();

        // VALID TOKEN
        if(!$activitytoken->isEmpty()) {

            // GET THE TOKEN RECORD FROM DATABASE
            $activitytoken = $activitytoken->first();
            $user = $activitytoken->user()->frontuser()->get();

            // USER ASSOCIATED WITH THIS TOKEN FOUND
            if(!$user->isEmpty()) {

                $data['type'] = 'success';
                $data['token'] = $token;

            }

        }

        return view('resetpassword', $data);
    }

    public function resetpasswordsubmit(Request $request) {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];

            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
            	'token'			=> 'required',
				'password'      => 'required|confirmed'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

            	$token = base64_decode(trim($request->token));
            	$activitytoken = Activitytoken::resetpassword()->where('token', $token)->get();

                if(!$activitytoken->isEmpty()) {

                	$activitytoken = $activitytoken->first();
                	$user = $activitytoken->user()->frontuser()->get();

                	if(!$user->isEmpty()) {

                		$user = $user->first();
		                $user->password = Hash::make($request->password);

		                if($user->save()) {

		                	$data['type'] = 'success';
		                	$activitytoken->delete();

		                }
		                else {

		                	$data['type'] = 'error';
		                	$data['caption'] = 'Unable to reset your password. Please try again.';

		                }

                	}
                	else {

						$data['type'] = 'error';
                		$data['caption'] = 'No such user found.';

                	}

                }
                else {

                	$data['type'] = 'error';
                	$data['caption'] = 'Pasword reset link expired or doesn\'t exist.';

                }

            }
            // VALIDATION FAIL
            else {

                $errors = $validator->errors()->all();
                $data['type'] = 'error';
                $data['caption'] = $errors[0];
                $data['errorfields'] = $validator->errors()->keys();

            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

}
