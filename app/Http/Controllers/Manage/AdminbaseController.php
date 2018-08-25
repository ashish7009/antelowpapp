<?php namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use View;

class AdminbaseController extends Controller {

	protected $globaldata = [];

    public function __construct() {

        $this->middleware('adminauth');

    	if(Auth::admin()->check()) {
    		$admin = Auth::admin()->get();
    		$this->globaldata['admin'] = $admin;
    		View::share('globaldata', $this->globaldata);
    	}
    }

    public function isSuperAdmin() {
        if($this->globaldata['admin']->issuperadmin == 1) {
            return true;
        }
        else {
            return false;
        }
    }
    
}

