<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushNotification extends Controller
{
   	private  static  $API_ACCESS_KEY = "AAAAotp-Row:APA91bEoQkTibbaJfh-XTfjlp2Tn8iIY2wBADKabmg_0l-SysdUr4ZV7XC4ZgjNceC_WiOXBAagPEHlTYSgLPiOpPe_AecsmBbIHKuz8AvYvrLt9jUDIxM8fiV6NQ9IU4f3R84wze_r9";
	private static $passphrase = '123456';

	public function __construct() 
	{
		// exit('Init function is not allowed');
	}

	public function android($data, $reg_id) 
		{
			$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
			
			$notification = [
				'title' =>	$data['mtitle'],
				'body' 	=> 	$data['mdesc'],
				'sound' => 	'default'
			];
			$extraNotificationData = ["sound" => "default","title" => $data['mtitle'],"message" => $data['mdesc'],"moredata" => $data['mdesc']];
			
			$fcmNotification = [
				'to'        	=> $reg_id, //single token
				'notification' 	=> $notification,
				'data'			=> $extraNotificationData
			];
			
			$content = "<br/>".date('d-m-Y H:i:s')."  -  ".$reg_id;
			
			$headers = [
				'Authorization: key=' . self::$API_ACCESS_KEY,
				'Content-Type: application/json'
			];
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$fcmUrl);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
			$result = curl_exec($ch);
			curl_close($ch);
			
			$content .= "  -  ".json_encode($fcmNotification)." - ".$result;
			
			// file_put_contents('logs/anroidlog.html',"\n".$content,FILE_APPEND);
				
			return $result;
		}

		public function iOS($data, $devicetoken,$pathPerm) 
		{
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $pathPerm);
			stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);

			$content = "<br/>".date('d-m-Y H:i:s')."  -  ".$devicetoken;
			
			// Open a connection to the APNS server
			//$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
			$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
			if (!$fp) {
				//$status = "Failed to connect:" . $err ."=>". $errstr;
				$content .=	"Failed to connect: $err $errstr" . PHP_EOL;
				//echo json_encode(array('status' => $status));
				//exit;
			}
			// Create the payload body
			$body['aps'] = array(
				'alert' => $data['mdesc'],
				'sound' => 'default',
				'web_link' => (isset($data['weblink']))?$data['weblink']:''
			);

			// Encode the payload as JSON
			$payload = json_encode($body);

			$content .= "  -  ".$payload." - ";
			
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $devicetoken) . pack('n', strlen($payload)) . $payload;

			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
			$status = ($result) ? 'success' : 'fail';

			$content.=  "Result : ".$status;
			
			// Close the connection to the server
			fclose($fp);
			
			// file_put_contents('logs/ioslog.html',"\n".$content,FILE_APPEND);
			
			return array('status' => $status);
		}
}
