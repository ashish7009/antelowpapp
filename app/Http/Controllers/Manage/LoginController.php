<?php namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller {    

	public function __construct() {
		$this->middleware('adminguest');
	}

	public function login() {
		return view('manage.login');
	}
	
	public function submit(Request $request) {

		// if ajax request
		if ($request->ajax()) {
			
			$rules = [
					'email'   => 'required|email',
					'password'   => 'required'
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) {

				$errors = $validator->errors()->all();
				$data['type'] = 'error';
				$data['caption'] = 'One or more invalid input found.';
				$data['errorfields'] = $validator->errors()->keys();

			}
			else {

				$email = trim($request->email);
				$password = $request->password;
				$remember = 0;
				if($request->remember){
					$remember = 1;
				}

				if(Auth::admin()->attempt(['email' => $email,'password' => $password, 'status' => 1, 'usertype' => 1], $remember)) {
					$data['type'] = 'success';
					$data['redirectUrl'] = url('/manage/dashboard');
				}
				else {
					$data['type'] = 'error';
					$data['caption'] = 'Invalid email address or passowrd.';
				}

			}

			return response()->json($data);
		}
		else{
			return 'No direct access allowed!';
		}
	}
}

