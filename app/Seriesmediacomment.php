<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Seriesmediacomment extends Model {

	protected $table = 'seriesmediacomments';

	protected $primaryKey = 'seriesmediacommentid';

	public function seriesmedia() {
		return $this->belongsTo('App\Seriesmedia', 'seriesmediaid');
	}

	public function user() {
		return $this->belongsTo('App\User', 'userid');
	}

	public function scopeOfUser($query, $userid) {
		return $query->Where('userid', $userid);
	}

	public function scopeActive($query){
		return $query->where('status', 1);
	}

}
