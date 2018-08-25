<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Mediaclick extends Model {

	protected $table = 'mediaclicks';

	protected $primaryKey = 'mediaclickid';

	public function seriesmedia() {
		return $this->belongsTo('App\Seriesmedia', 'seriesmediaid');
	}

	public function user() {
		return $this->belongsTo('App\User', 'userid');
	}

}
