<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NameController extends FrontbaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function selectedUser(Request $request)
	{
		//return $request->all();
		$influence_users = User::where('influencer',1)->get();
		if(!empty($influence_users))
		{
			foreach ($influence_users as $influence_user) {
				$influencer = User::find($influence_user->userid);
				$influencer->influencer = 0;
				$influencer->save();
			}
		}

		$users = explode(",", $userselected);
		foreach ($users as $id) {
			$user = User::find($id);
			$user->influencer = 1;
			$user->save();
		}

		if($users){
			echo 'OK';
		}
		else
		{
			echo 'NO';
		}
	}
}
