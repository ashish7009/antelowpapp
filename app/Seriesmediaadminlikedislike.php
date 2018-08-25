<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Seriesmediaadminlikedislike extends Model {

	protected $table = 'seriesmediaadminlikesdislikes';

	protected $primaryKey = 'seriesmediaadminlikesdislikeid';

	protected $fillable = ['seriesmediaid','userid', 'no_of_likes'];

//	public function scopeNoOfLikes($query) {
//		return $query->Where('no_of_likes', 1);
//	}
    public function seriesmedia() {
        return $this->belongsTo('App\Seriesmedia', 'seriesmediaid');
    }
//
    public function user() {
        return $this->belongsTo('App\User', 'userid');
    }

    public function scopeOfUser($query, $userid) {
        return $query->Where('userid', $userid);
    }

}
