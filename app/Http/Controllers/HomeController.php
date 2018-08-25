<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;

use App\Seriesmedia;
use App\User;
use App\Http\Controllers\PushNotification;
use App\Notification;
use App\Notificationlog;

class HomeController extends FrontbaseController {

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index() {
		$data = [];
		$data['page_home'] = true;
        $data['hideheaderanimation'] = true;
        //$data['seriesmedias'] = Seriesmedia::active()->orderBy('seriesmediaid')->get();
		$urlc=$_SERVER['HTTP_HOST'];
		/*if (strpos($urlc, 'schemk') !== false) {
       return redirect('http://antelowpapp.com/');
}else{*/
        return view('home', $data);

		
		//}
	}

	public function getpaid() {
		$data = [];
        $data['hideheaderanimation'] = true;
        return view('getpaid', $data);
	}

	public function upload() {
		return view('upload');
	}

	public function upload_days() {
		return view('upload_days');
	}
	
	public function schedule_series(){
		$users = User::frontuser()->get();
		foreach ($users as $user){
			$episodes_will_air_today  = Seriesmedia::active()->ofuser($user->userid)->noimmidiatepublish()->airtoday()->orderBy('publishdate', 'desc')->get();
			$episodes_will_air_today_count  = Seriesmedia::active()->ofuser($user->userid)->noimmidiatepublish()->airtoday()->orderBy('publishdate', 'desc')->get()->count();

            echo "<pre>"; print_r($episodes_will_air_today);echo "</pre>";
            \Log::info("function started  ".$episodes_will_air_today_count);
			foreach($episodes_will_air_today as $episode){
			//push Notification
		            $title = 'Potatoes';
		            $comment_msg = $user->fullname." just aired a new post";
		
		             $comment_msgPayload = array(
		                'mtitle'    => $title,
		                'mdesc'     => $comment_msg,
		            );
		
		            $comment_PNO = new PushNotification();
		            $comment_receiverUserToken = User::find($user->userid);
		            $comment_registration_id = $comment_receiverUserToken->registration_id;
		            $data['comment_registration_id'] = $comment_registration_id;
		            $comment_access_token = $comment_receiverUserToken['access_token'];
		            $comment_status = '';
                    $pathPerm ='';
		            if(!empty($comment_access_token))
		            {
		                $comment_status = $comment_PNO->iOS($comment_msgPayload,$comment_access_token,'');
		            }
		            if(!empty($comment_registration_id))
		            {
		                $comment_status = $comment_PNO->android($comment_msgPayload,$comment_registration_id);
		            }
		
		             $notifylog_like = new Notificationlog();
		             $notifylog_like->title = $title;
		             $notifylog_like->message = $comment_msg;
		             $notifylog_like->userid = $user->userid;
		             $notifylog_like->status = $comment_status;
		             $notifylog_like->save(); 
		             //print_r($notifylog_like);
				

			}
		    
		}
		
	        \Log::info("function started");
	        	      
	       \Log::error("Another log data");
	}

	

}
