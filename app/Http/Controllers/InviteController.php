<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontpostloginbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Seriesmedia;
use App\Emailtemplate;

class InviteController extends FrontpostloginbaseController {
    
    public function invitefriends(Request $request) {
        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'emails'    => 'required',
//                'message'   => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $data['type'] = 'error';
                $data['caption'] = 'Unable to invite your friends at this moment. Kindly try after some time.';
                
                $allok = true;
                $seriesmediaid = intval($request->seriesmediaid);
                $invite_friend_link = '';
                $link_message = '';
                if($seriesmediaid > 0) {
                    $seriesmedia = Seriesmedia::find($seriesmediaid);
                    if(!empty($seriesmedia)) {
                        $invite_friend_link = url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
                        $link_message = 'Here is the link to the series,';
                    }
                    else {
                        $allok = false;
                    }
                }
                else {
                    $invite_friend_link = url('/user/signup');
                    $link_message = 'Here is the link to the signup,';
                }

                if($allok) {

                    $emails = explode(',', trim($request->emails));
                    $emails_valid = true;
                    foreach ($emails as $index => $email) {
                        if(!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                            $emails_valid = false;
                        }
                        else {
                            $emails[$index] = trim($email);
                        }
                    }

                    if($emails_valid) {

                        $message = trim($request->message);
                        $user = $this->globaldata['user'];
                        $emailtemplateid = config('app.constants.emailtemplates.invitefriendsmail');
                        $sendmailresult = true;

                        foreach ($emails as $email) {

                            $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();

                            if(!$emailtemplate->isEmpty()) {

                                $emailtemplate = $emailtemplate->first();

                                // SET ARRAY OF REPLACE STRINGS AND ACTUAL VALUES
                                $core_array = ['#friend_email#', '#user_name#', '#user_email#', '#message#', '#link_message#', '#invite_friend_link#'];
                                $dynamic_array = [
                                    $email,
                                    $user->fullname,
                                    $user->email,
                                    nl2br($message),
                                    $link_message,
                                    $invite_friend_link
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
                    
                        // SEND EMAIL SUCCESS
                        if($sendmailresult) {

                            $data['type'] = 'success';
                            $data['caption'] = 'Friend invitation sent successfully.';

                        }

                    }
                    else {
                        $data['caption'] = 'One or more email addresses entered is incorrect.';
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