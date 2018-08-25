<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Followuser extends Model {

	protected $table = 'followusers';

	protected $primaryKey = 'followuserid';

	public function follower() {
		return $this->belongsTo('App\User', 'followerid', 'userid');
	}
}
