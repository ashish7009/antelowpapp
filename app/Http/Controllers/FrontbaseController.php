<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use View;
use App\Seriesmedialikedislike;
use App\Followuser;
use App\Seriesmedia;
use App\User;

class FrontbaseController extends Controller {

	protected $globaldata = [];
	
    public function __construct() {

    	if(Auth::user()->check()) {

    	    $user = Auth::user()->get();
            $this->globaldata['user'] = $user;
            $this->globaldata['notifications'] = $user->notifications()->orderBy('is_viewed', 'ASC')->orderBy('notificationid', 'DESC')->get();
          //  echo "<pre>"; print_r($this->globaldata['notifications']); echo "</pre>";
       //    foreach($this->globaldata['notifications'] as $nf){
        //echo $nf->notificationid ."\n";
        //}
            $count = count($user->notifications()->where('is_viewed','=' ,'0')->get());
            $this->globaldata['notificationtopcounts'] = $count ;
            $notificationcounts = count($this->globaldata['notifications']);
            $episodes_will_air_today = Seriesmedia::active()->noimmidiatepublish()->airtoday()->get()->count();
            if($episodes_will_air_today > 0) {
                $notificationcounts++;    
            }
            $this->globaldata['episodes_will_air_today_count'] = $episodes_will_air_today;
            $justairedepisodes = Seriesmedia::active()->ofuser($user->userid)->noimmidiatepublish()->airedinlasthour()->orderBy('publishdate', 'desc')->get();
            if(!$justairedepisodes->isEmpty()) {
                $notificationcounts += count($justairedepisodes);
            }
            $this->globaldata['justairedepisodes'] = $justairedepisodes;
            $this->globaldata['notificationcounts'] = $notificationcounts;
            $this->globaldata['myseriesmedialikes'] = array_column(Seriesmedialikedislike::ofUser($user->userid)->likes()->get()->toArray(), 'seriesmediaid');
            //$this->globaldata['userfollow'] = array_column(Followuser::get()->toArray(), 'followuserid');
            
    	}
        View::share('globaldata', $this->globaldata);

    }

}

