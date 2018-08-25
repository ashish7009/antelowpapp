<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InfluencerController extends FrontbaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return view('influencer');
	}

	public function getUserList()
	{
		$users = User::where('usertype',2)->get();
		return json_encode($users);
	}

	public function verifyUser(Request $request)
	{
		 if(Auth::attempt(array('email' => 'eea2130@columbia.edu', 'password' => $request->password)))
		 {
		 	echo 'OK';
		 }
		 else {
		 	echo 'NO';
		 }
	}

	public function selectedUser($userselected)
	{
		// return $userselecteder;
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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
