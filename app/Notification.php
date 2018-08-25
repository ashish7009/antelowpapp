<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

	protected $table = 'notifications';

	protected $primaryKey = 'notificationid';

	protected $appends = ['notificationtext', 'notificationurl','notificationimage','notificationdataid'];

	
	public function getNotificationimageAttribute() {
		if($this->type == 1) {
			//$usersrc = $this->contentlikes->user->imagefilepath;
			return $usersrc=secure_asset('manage/images/noimage-user.png');
		}
		else if($this->type == 2) {
			
			$usersrc = $this->contentcomment->user->imagefilepath;
			return $usersrc;
		}
		else if($this->type == 3) {
				
			$usersrc = $this->contentfollow->follower->imagefilepath;
			return $usersrc;
		}
		else if($this->type == 4) {
			
			$usersrc = $this->contentseriesmedia->series->user->imagefilepath;
			return $usersrc;
		}
		else if($this->type == 5) {
			$usersrc = "";
			return $usersrc;
		}
		else if($this->type == 6){
			$usersrc = secure_asset('manage/images/noimage-user.png');
			return $usersrc;
		}
		else if($this->type == 7){
			$usersrc =secure_asset('manage/images/noimage-user.png');
			return $usersrc;
		}
		else if($this->type == 8){
			$usersrc = secure_asset('manage/images/noimage-user.png');
			return $usersrc;
		}
		return '';
	}
	
	public function getNotificationtextAttribute() {
		//dd($this->type);
		if($this->type == 1) {
			//$n=ucfirst($this->contentlikes->user->firstname);
			//dd(gettype($n));
			//$firstname = '<span class="fw-notification">$n</span> Just liked your post ';
			//dd($firstname);
			return $firstname='';
			
		}
		else if($this->type == 2) {
			$firstname = "<span class='fw-notification'>".ucfirst($this->contentcomment->user->firstname)."</span>"." just posted a comment ";
			return  $firstname;
		}
		else if($this->type == 3) {
				$firstname = "<span class='fw-notification'>".ucfirst($this->contentfollow->follower->firstname)."</span>"." is now following ";
			return $firstname;
		}
		else if($this->type == 4) {
		//	$firstname = "<span class='fw-notification'>".ucfirst($this->contentseriesmedia->series->user->firstname)."</span>"." just aired a new post";
			return $firstname='';
		}
		else if($this->type == 5) {
			if($this->content_text == 1)
			{
				return $this->content_text . ' person want to see your post';
			}
			else
			{
				return $this->content_text . ' people want to see your post';
			}
		}
		else if($this->type == 6) {
			$firstname = "Someone just liked "."<span class='fw-notification'>".ucfirst($this->like_post_name)."'s</span>"." post";
			return $firstname;
		}
		else if($this->type == 7) {
			$firstname = "someone just commented on "."<span class='fw-notification'>".ucfirst($this->comment_post_name)."'s</span>"." post";
			return $firstname;
		}
		else if($this->type == 8){
			$firstname = "someone wants to see "."<span class='fw-notification'>".ucfirst($this->request_post_name)."'s</span>"." post";
			return $firstname;
		}
		return '';
	}

	public function getNotificationurlAttribute() {
		if($this->type == 1) {
			$seriesmedia = $this->contentlikes->seriesmedia;
			return secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
		}
		else if($this->type == 2) {
			$seriesmedia = $this->contentcomment->seriesmedia;
			return secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
		}
		else if($this->type == 3) {
			return secure_url('user/view/'.$this->contentfollow->follower->userid);
		}
		else if($this->type == 4) {
			$seriesmedia = $this->contentseriesmedia;
			return secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
		}
		else if($this->type == 5) {
			$seriesmedia = $this->contentseriesmedia;
			return secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
		}
		else if($this->type == 6){
			$seriesmedia = $this->contentlikes->seriesmedia;
			return secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
		}
		else if($this->type == 7){
			$seriesmedia = $this->contentcomment->seriesmedia;
			return secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
		}
		else if($this->type == 8) {
			$seriesmedia = $this->contentseriesmedia;
			return secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia));
		}
		return '';
	}
	public function getNotificationDataidAttribute(){
		if($this->type == 1) {
			$this->notificationid;
		}
		else if($this->type == 2) {
			$this->notificationid;
		}
		else if($this->type == 3) {
			$this->notificationid;
		}
		else if($this->type == 4) {
			$this->notificationid;
		}
		else if($this->type == 5) {
			$this->notificationid;
		}
		else if($this->type == 6){
			$this->notificationid;
		}
		else if($this->type == 7){
			$this->notificationid;
		}
		else if($this->type == 8) {
			$this->notificationid;
		}
		return '';
	}

	public function scopeDesc($query) {
		return $query->latest('notificationid');
	}

	public function scopeOflike($query) {
		return $query->where('type', 1);
	}

	public function scopeOfcomment($query) {
		return $query->where('type', 2);
	}

	public function scopeOffollow($query) {
		return $query->where('type', 3);
	}

	public function scopeOfnewvideo($query) {
		return $query->where('type', 4);
	}

	public function contentlikes() {
		return $this->belongsTo('App\Seriesmedialikedislike', 'contentid', 'seriesmedialikesdislikeid');
	}

	public function contentcomment() {
		return $this->belongsTo('App\Seriesmediacomment', 'contentid', 'seriesmediacommentid');
	}

	public function contentfollow() {
		return $this->belongsTo('App\Followuser', 'contentid', 'followuserid');
	}

	public function contentseriesmedia() {
		return $this->belongsTo('App\Seriesmedia', 'contentid', 'seriesmediaid');
	}
}
