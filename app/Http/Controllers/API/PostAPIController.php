<?php
namespace App\Http\Controllers\API;

//use Illuminate\Http\Request;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\User;
use App\Notificationlog;
use Validator;
use Request;
use Illuminate\Http\Response;
   use Illuminate\Support\Facades\DB;
class PostAPIController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $posts = Post::all();
    //     return $this->sendResponse($posts->toArray(), 'Posts retrieved successfully.');
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        return json($input);

        // $validator = Validator::make($input, [
        //     'name' => 'required',
        //     'description' => 'required'
        // ]);


        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());       
        // }


        // $post = Post::create($input);


        // return $this->sendResponse($post->toArray(), 'Post created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request)
    // { 
    //     // $email=$request->username;
    //    // $fcm_token=$request->fcm_token;
    //    $all = $request->all();
    //    //   $post = User::find($id);
        
    //    //  if (is_null($post)) {
    //    //      return $this->sendError('Post not found.');
    //    //  }
    //    //  else
    //    //  {
    //         $user = User::where('userid','=','6')->update(['registration_id' => serialize($all)."==".$request->fcm_token."==".$request->username]);
    //    //  }
    //    // $post = User::find($id);
    //     // return $this->sendResponse($post->toArray(), 'Post retrieved successfully.');
    // }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        return $input;

        // $validator = Validator::make($input, [
        //     'name' => 'required',
        //     'description' => 'required'
        // ]);


        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());       
        // }


        // $user = User::find($id);
        // if (is_null($user)) {
        //     return $this->sendError('User not found.');
        // }


        // $user->registration_id = $input['name'];
        // $post->description = $input['description'];
        // $post->save();


        // return $this->sendResponse($post->toArray(), 'Post updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $post = Post::find($id);


    //     if (is_null($post)) {
    //         return $this->sendError('Post not found.');
    //     }


    //     $post->delete();


    //     return $this->sendResponse($id, 'Tag deleted successfully.');
    // }

    public function settoken(Request $request)
    {
$input= Request::all();
$email=$input['username'];
$token=$input['fcm_token'];

if(empty($token)){
    return true;
}

$users = User::frontuser()->where('registration_id',$token)->get()->count();
if($users > 0){
  $affectedRows = User::where('registration_id', '=', $token)->update(['registration_id' => '']);
}
//$affectedRows = User::where('email', '=', $email)->update(['registration_id' => $token]);
$user=DB::update('update users set registration_id= ? where email= ?', [$token,$email]);
 $newUser=DB::select('select * from users where email = ?', [$email]);

        return  response()->json($newUser, 200);


    }
}