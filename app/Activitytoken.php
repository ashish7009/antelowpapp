<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Activitytoken extends Model {

	protected $table = 'activitytokens';

	protected $primaryKey = 'activitytokenid';

	public function user() {
		return $this->belongsTo('App\User', 'userid');
	}

	public function scopeVerifyregistration($query) {
		return $query->Where('type', 1);
	}

	public function scopeVerifyemail($query) {
		return $query->Where('type', 2);
	}

	public function scopeResetpassword($query) {
		return $query->Where('type', 3);
	}
}
