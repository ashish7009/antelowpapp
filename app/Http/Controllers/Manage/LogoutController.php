<?php namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller {    
	
	public function logout(){
		Auth::admin()->logout();
		return redirect('/manage/login');
	}

}

