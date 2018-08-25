<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Seriesmedialikedislike extends Model {

	protected $table = 'seriesmedialikesdislikes';

	protected $primaryKey = 'seriesmedialikesdislikeid';

	public function scopeLikes($query) {
		return $query->Where('type', 1);
	}

	public function scopeDislikes($query) {
		return $query->Where('type', 0);
	}

	public function seriesmedia() {
		return $this->belongsTo('App\Seriesmedia', 'seriesmediaid');
	}

	public function user() {
		return $this->belongsTo('App\User', 'userid');
	}

	public function scopeOfUser($query, $userid) {
		return $query->Where('userid', $userid);
	}

}
