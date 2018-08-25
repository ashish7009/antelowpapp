<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;

class FrontpostloginbaseController extends FrontbaseController {
	
    public function __construct() {

    	Parent::__construct();

    	$this->middleware('userauth');

    }

}