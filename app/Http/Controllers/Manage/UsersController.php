<?php namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Emailtemplate;
use App\User;

class UsersController extends AdminbaseController {

	/* users list page */	
    public function index() {
    	$data = ['menu_users' => true]; 
        return view('manage.users', $data);
    }
    
    /* users list data through ajax  */
    public function load(Request $request) {
    	// if ajax request
    	if ($request->ajax()) {
    		$users = User::frontuser()->search($request->search)->latest('userid')->paginate(config('app.constants.perpage'));
			$data['users'] = $users;
    		return view('manage.ajax.users', $data);
    	}
		// if non ajax request
		else {
			return 'No direct access allowed!';
		}
    }
	
    /* users add page */
    public function add() {
    	$data = ['menu_users' => true];
    	$user = new User();
    	$data['user'] = $user;
    	return view('manage.user', $data);
    }
    
    /* users edit page */
    public function edit($id) {
    	$user = User::frontuser()->find($id);
    	if(!empty($user)) {
    		$data = ['menu_users' => true];
    		$data['user'] = $user;
    		return view('manage.user', $data);
    	}
    	else {
    		return view('manage.errors.404');
    	}
    }
    
    /* user add / update code */
    public function store(Request $request) {
    	// if ajax request
    	if ($request->ajax()) {    	
    		    		
    		$data = [];    		
    		$userid = intval($request->userid);
    		$email  = trim($request->email);
    		
    		// make validation rules for received data
    		$rules = [
    				'firstname'	    => 'required',
                    'lastname'      => 'required',
    				'email'         => 'required|email',
                    'phoneno'       => 'required|numeric',
                    'address1'      => 'required',
                    'country'       => 'required',
                    'state'         => 'required',
                    'city'          => 'required',
                    'pincode'       => 'required|numeric',
    				'imagefile'     => 'mimes:jpg,jpeg,png'
    		];
    		
    		if($userid == 0) {
    			$rules['password'] = 'required|confirmed';
    		}
            else {
    			$rules['password'] = 'confirmed';
    		}
    		
    		// validate received data
    		$validator = Validator::make($request->all(), $rules);
    		 
    		$validator->after(function($validator) use ($userid, $email) {
    			// check provided email availability
    			$res = User::where('userid', '!=', $userid)->where('email', $email)->get();
    			if(count($res) > 0) {
    				$validator->errors()->add('email', 'Email is already in use. Please try different email.');
    			}
    		});
    		
    		// if validation fails
    		if ($validator->fails()) {
    			$data['type'] = 'error';
    			$data['caption'] = 'One or more invalid input found.';
    			$data['errorfields'] = $validator->errors()->keys();
    			$data['errormessage'] = $validator->errors()->all()[0];
    		}
    		// if validation success
    		else {

                $user = ($userid == 0) ? new User() : User::frontuser()->find($userid);
    			 
                $user->firstname    = trim($request->firstname);
                $user->lastname     = trim($request->lastname);
                $user->email        = trim($request->email);
                $user->phoneno      = trim($request->phoneno);
                $user->city         = trim($request->city);
                $user->state        = trim($request->state);
                $user->country      = trim($request->country);
                $user->address1     = trim($request->address1);
                $user->address2     = trim($request->address2);
                $user->pincode      = trim($request->pincode);
                $user->usertype     = 2;
                $user->issuperadmin = 0;
                $user->status       = (Input::get('status'))?1:0;
    			
    			if($request->password != '') {
    				$password = $request->password;
    				$user->password = Hash::make($password);
    			}
    			
    			// add
    			if($userid == 0) {
    				$result = $user->save();
    				$captionsuccess = 'User added successfully.';
    				$captionerror = 'Unable to add user. Please try again.';
    			}
    			// edit
    			else {
    				$result = $user->update();
    				$captionsuccess = 'User updated successfully.';
    				$captionerror = 'Unable to update user. Please try again.';
    			}
    		
    			// database insert/update success
    			if($result) {

    				$data["type"] = "error";
    				
	                $imgpath = public_path($user->userdir);

	                // delete image if set to true
	                if(intval($request->deleteimage) == 1) {

                        // delete old image file if any
                        if($user->hasimage) {
                            File::deleteDirectory($imgpath);
                        }               	

                        $user->imagefile = '';
                        $user->update();
	                }

					// UPLOAD USER IMAGE IF SELECTED
                    $imageuploadresult = uploadUserImage($user, $request);
                    if($imageuploadresult) {
                        $data["type"] = "success";
                    }
	                
	                /*if($data['type'] == 'success') {

                        // send registration mail to user
                        if($userid == 0) {
                            // get email template
                            $emailtemplateid = config('app.constants.emailtemplates.newuserregistraion');
                            $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();
                        
                            if(!$emailtemplate->isEmpty()) {
                                $emailtemplate = $emailtemplate->first();
                        
                                // set array of replace strings and actual values
                                $core_array = array('#user_name#', '#user_email#', '#user_password#', '#loginlink#');
                                $dynamic_array = array(
                                        $user->firstname,
                                        $user->email,
                                        $password,
                                        url('/')
                                );
                        
                                // replace dynamic data in mailtemplate
                                $emailtemplate->replaceDynamicData($core_array, $dynamic_array);
                        
                                // send mail
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
                                        
                                // mail sending failed
                                if(count(Mail::failures()) > 0) {
                                    $data['type'] = 'error';
                                    $data['caption'] = $captionsuccess.' But unable to send mail to user.';
                                }
                            }
                        }
                        else {

                            if(isset($password)) {

                                // send password changed mail to user
                                // get email template
                                $emailtemplateid = config('app.constants.emailtemplates.userpasswordchangedbyadmin');
                                $emailtemplate = Emailtemplate::active()->where('emailtemplateid', $emailtemplateid)->get();
                            
                                if(!$emailtemplate->isEmpty()) {
                                    $emailtemplate = $emailtemplate->first();
                            
                                    // set array of replace strings and actual values
                                    $core_array = array('#user_name#', '#user_email#', '#user_password#', '#loginlink#');
                                    $dynamic_array = array(
                                            $user->firstname,
                                            $user->email,
                                            $password,
                                            url('/')
                                    );
                            
                                    // replace dynamic data in mailtemplate
                                    $emailtemplate->replaceDynamicData($core_array, $dynamic_array);
                            
                                    // send mail
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
                                            
                                    // mail sending failed
                                    if(count(Mail::failures()) > 0) {
                                        $data['type'] = 'error';
                                        $data['caption'] = $captionsuccess.' But unable to send mail to user.';
                                    }
                                }

                            }
                        }
	                	
	                }*/
	                
    				if($data["type"] == 'success') {
    					$data['caption'] = $captionsuccess;
    					$data['redirectUrl'] = url('/manage/users');
    				}
    			}
    			// database insert/update fail
    			else {
    				$data['type'] = 'error';
    				$data['caption'] = $captionerror;
    			}
    		}
    	        	
    		return response()->json($data);
    	
    	}
    	// if non ajax request
    	else {
    		return 'No direct access allowed!';
    	}
    }
    
    /* user delete */
    public function destroy(Request $request) {
    	// if ajax request
    	if ($request->ajax()) {

            $data = [];
    		$user = User::frontuser()->find($request->userid);
    		
    		if(!empty($user)) {

                $userdir = public_path($user->userdir);
                $files_deleted = true;

                // delete user image file if any
                if($user->hasimage) {
                    if(!File::deleteDirectory($userdir)) {
                        $files_deleted = false;
                    }
                }
                 
                // if physical files deleted then delete entry from database
                if($files_deleted) {             

                    if($user->delete()) {                       
                        $data['type'] = 'success';
                        $data['caption'] = 'User deleted successfully.';
                    }
                    else {
                        $data['type'] = 'error';
                        $data['caption'] = 'Unable to delete user.';
                    }

                }
                // physical files not deleted
                else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Unable to delete user.';
                }

    		}
    		else {
    			$data['type'] = 'error';
    			$data['caption'] = 'Invalid user.';
    		}
    		        			
    		return response()->json($data);
    	}
    	// if non ajax request
    	else {
    		return 'No direct access allowed!';
    	}
    }

    public function influencerssubmit(Request $request) {
        // if ajax request
        if ($request->ajax()) {

            $userid = intval($request->userid);
            $isinfluencer = (Input::get('isinfluencer'))?1:0;

            $rules = [];
            if($isinfluencer == 1) {
                $rules['likeinfluencer'] = 'required|min:0';
                $rules['followerinfluencer'] = 'required|min:0';
            }

            $validator = Validator::make($request->all(), $rules);

            // validation fails
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();
            }
            else {

                $user = User::frontuser()->find($userid);

                if(!empty($user)) {
                    
                    $user->isinfluencer  = $isinfluencer;
                    $user->likeinfluencer  = intval($request->likeinfluencer);
                    $user->followerinfluencer  = intval($request->followerinfluencer);

                    if($user->update()) {

                        $data['type'] = 'success';
                        $data['caption'] = 'Influencers updated successfully.';                    

                    }
                    else {
                        $data['type'] = 'error';
                        $data['caption'] = 'Unable to update influencers. Please try again.';
                    }
                }
                else {
                    $data['type'] = 'error';
                    $data['caption'] = 'User does not exist.';
                }
                
            }

            return response()->json($data);

        }
        else {
            return 'No direct access allowed!';
        }
    }
    
}
?>