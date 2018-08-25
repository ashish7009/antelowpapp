<?php namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\AdminbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\User;

class AdminController extends AdminbaseController {

	public function index() {
		$data = [];
		$data['menu_dashboard'] = true;
		return view('manage.dashboard', $data);
	}

    public function profile() {
        $data = [];
        $data['menu_profile'] = true;
        $data['admin'] = $this->globaldata['admin'];
        return view('manage.profile', $data);
    }

    public function submit(Request $request){
        // if ajax request
        if ($request->ajax()) {
        	$email = trim($request->email);
            $rules = array(
	            'firstname'     => 'required',
	            'lastname'     	=> 'required',
	            'email'    		=> 'required|email',
	            'phoneno'       => 'required|numeric',
	            'imagefile' 	=> 'mimes:jpeg,jpg,png'
	        );

            if($request->password != '' || $request->password_confirmation != '' || $request->oldpassword != '') {
				$rules['password'] = 'required|confirmed';
				$rules['password_confirmation'] = 'required';
				$rules['oldpassword'] = 'required';
			}

	        $validator = Validator::make($request->all(), $rules);
	        $userid = $request->userid;

	        $validator->after(function($validator) use ($userid, $email) {
	        	// check provided email availability
	        	$res = User::where('userid', '!=', $userid)->where('email', $email)->get();
	        	if(count($res) > 0) {
	        		$validator->errors()->add('email', 'Email is already in use. Please try different email.');
	        	}
	        });
	       
	        $user = User::find($userid);

	        // Confirm old password
	        $validate_password = true;
	        if($request->oldpassword != '') {
	            $oldDBPassword = $user->password;
	            $validate_password = Hash::check($request->oldpassword, $oldDBPassword);
	        }

	        // validation fails
	        if ($validator->fails() || !$validate_password) {
	            $errors = $validator->errors()->all();
	            $data['type'] = 'error';
	            if($validator->fails()) {
	                $data['caption'] = 'One or more invalid input found.';
	                $data['errorfields'] = $validator->errors()->keys();
	            }
	            if(!$validate_password) {
	                $data['caption'] = 'Entered old password is invalid.';
	            }
	        }
	        else {
	            $user->firstname  = trim($request->firstname);
	            $user->lastname  = trim($request->lastname);
	            $user->email = $email;
	            $user->phoneno      = $request->phoneno;
	            if($request->oldpassword != '') {
	            	$user->password = Hash::make($request->password);
	            }

	            if($user->update()) {

	                $data['type'] = 'success';
	                $data['caption'] = 'Profile updated successfully.';
	                $data['redirectUrl'] = url('manage/profile');

	                $filedir = config('app.constants.path_user').$userid.'/';
	                $imgpath = public_path($filedir);

	                // delete image if set to true
	                if(intval($request->deleteimage) == 1) {

	                	// delete old image file
		                if(File::deleteDirectory($imgpath)) {
		                	$user->imagefile = '';
	                		$user->update();
		                }
	                	
	                }

					// upload image file if exist
	                if ($request->hasFile('imagefile')) {

	                	if($request->file('imagefile')->isValid()) {
	                	
		                    $imagefile   = $request->file('imagefile');
		                    
		                    $extension = $request->file('imagefile')->getClientOriginalExtension();

		                    // delete old image file
		                    File::deleteDirectory($imgpath);
		                    
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
			                    }
			                    else {
			                        $data['type'] = 'error';
			                        $data['caption'] = $data['caption']. ' But unable to upload profile picture.';
			                    }
		                    }
		                    else {
		                    	$data['caption'] = $data['caption']. ' But unable to upload profile picture.';	
		                    }
	                    }
	                    else {
	                    	$data['caption'] = $data['caption']. ' But invalid file uploaded.';
	                    }
	                }

	            }
	            else {
	                $data['type'] = 'error';
	                $data['caption'] = 'Unable to update profile. Please try again.';
	            }
	        }

            return response()->json($data);

        }
        else {
            return 'No direct access allowed!';
        }
    }

    public function pagenotfound() {
    	return view('manage.errors.404');
    }

}