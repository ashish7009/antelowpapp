<?php
if ( ! function_exists('getTempName') )
{
	function getTempName($path, $extension, $includepath=false) {
		do {
	
			$temp_file= $path . str_replace([' ', '.'], '_', microtime()) . '.' . $extension;
	
		} while(file_exists($temp_file));
	
		if($includepath)
			return $temp_file;
		else
			return basename($temp_file);
	}
}

if ( ! function_exists('random_color_part') )
{
	function random_color_part() {
	    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}
}

if ( ! function_exists('random_color') )
{
	function random_color() {
	    return '#'.random_color_part() . random_color_part() . random_color_part();
	}
}

if ( ! function_exists('seriesmedia_unique_string') )
{
    function seriesmedia_unique_string($seriesmedia) {
        return str_slug($seriesmedia->title).'_'.$seriesmedia->seriesmediaid;
    }
}

if ( ! function_exists('uploadUserImage') )
{
	function uploadUserImage($user, $request) {
	    $result = false;
        $imgpath = public_path($user->userdir);

        // UPLOAD IMAGE FILE IF EXIST
        if ($request->hasFile('imagefile')) {
            
            if($request->file('imagefile')->isValid()) {

            	// DELETE OLD FILE
		        File::deleteDirectory($imgpath);
                
                $imagefile   = $request->file('imagefile');
                $extension = $request->file('imagefile')->getClientOriginalExtension();
                $img = Image::make($imagefile);
                $img->fit(config('app.constants.user_image_width'), config('app.constants.user_image_height'), function ($constraint) {
                    $constraint->upsize();
                });
                $filecreated = File::makeDirectory($imgpath, 0777, true, true);

                if($filecreated) {

                    $fileName = getTempName($imgpath, $extension);
                    if($img->save($imgpath . $fileName)) {
                        $user->imagefile = $fileName;
                        $user->update();
                        $result = true;
                    }
                }
            }
        }
        else {
            $result = true;
        }
        return $result;
	}
}

if ( ! function_exists('sendRegistrationMailToUser') )
{
	function sendRegistrationMailToUser($user, $emailtemplate, $token) {

	    $result = true;
	    $verificationlink = url('/user/verify/'.base64_encode($token));

        // SET ARRAY OF REPLACE STRINGS AND ACTUAL VALUES
        $core_array = ['#user_name#', '#user_email#', '#verificationlink#'];
        $dynamic_array = [
                $user->firstname,
                $user->email,
                $verificationlink
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
            $result = false;
        }

        return $result;
	}
}

if ( ! function_exists('sendEmailVerificationMailToUser') )
{
	function sendEmailVerificationMailToUser($user, $emailtemplate, $token) {

	    $result = true;
	    $verificationlink = url('/user/email/verify/'.base64_encode($token));

        // SET ARRAY OF REPLACE STRINGS AND ACTUAL VALUES
        $core_array = ['#user_name#', '#user_email#', '#verificationlink#'];
        $dynamic_array = [
                $user->firstname,
                $user->email,
                $verificationlink
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
            $result = false;
        }

        return $result;
	}
}

if ( ! function_exists('sendPasswordResetMailToUser') )
{
    function sendResetPasswordMailToUser($user, $emailtemplate, $token) {

        $result = true;
        $resetpasswordlink = url('/user/reset-password/'.base64_encode($token));

        // SET ARRAY OF REPLACE STRINGS AND ACTUAL VALUES
        $core_array = ['#user_name#', '#user_email#', '#resetpasswordlink#'];
        $dynamic_array = [
                $user->firstname,
                $user->email,
                $resetpasswordlink
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
            $result = false;
        }

        return $result;
    }
}

if ( ! function_exists('addPrecedingZero') )
{
    function addPrecedingZero($value) {
        return intval($value) < 10 ? '0'.$value : $value;
    }
}