<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Emailtemplate;

class ContactusController extends FrontbaseController {
    
    public function index() {
        $data = [];
        $data['menu_contactus'] = true;
        return view('contactus', $data);
    }

    public function submit(Request $request) {
        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'name'    => 'required',
                'email'     => 'required|email',
                'message'   => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $data['type'] = 'error';
                $data['caption'] = 'Unable to submit your message. Kindly try after some time.';

                $emailtemplateid = config('app.constants.emailtemplates.contactusmailtoadmin');
                $name = trim($request->name);
                $email = trim($request->email);
                $message = trim($request->message);
                $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();

                if(!$emailtemplate->isEmpty()) {

                    $emailtemplate = $emailtemplate->first();

                    // SET ARRAY OF REPLACE STRINGS AND ACTUAL VALUES
                    $core_array = ['#user_name#', '#user_email#', '#message#'];
                    $dynamic_array = [
                        $name,
                        $email,
                        nl2br($message)
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
                            
                    // MAIL SENDING SUCCESS
                    if(count(Mail::failures()) == 0) {
                        $data['type'] = 'success';
                        $data['caption'] = 'Your message is submitted successfully. We will be right back to you.';
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
}