<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $primaryKey = 'userid';

	protected $hidden = ['password', 'remember_token'];

	protected $appends = ['userdir', 'imagefilepath', 'hasimage', 'statustext', 'fullname', 'newtoken'];

	public function getImagefilepathAttribute() {
		$imagefile = trim($this->imagefile);
		$userid = intval($this->userid);
		/* if(file_exists(public_path() . config('app.constants.path_user') . $userid . '/' . $imagefile) && $imagefile != '') {
			return asset(config('app.constants.path_user') . $userid . '/' . $imagefile);
		}
		else {
			return asset('manage/images/noimage-user.png');
		} */
		if(file_exists( 'uploads/' . $imagefile) && $imagefile != '') {
			return asset('uploads/' . $imagefile);
		}
		else {
			return asset('manage/images/noimage-user.png');
		}
	}

	public function getHasimageAttribute() {
		$imagefile = trim($this->imagefile);
		$userid = intval($this->userid);
		if(file_exists(public_path() . config('app.constants.path_user') . $userid . '/' . $imagefile) && $imagefile != '') {
			return true;
		}
		return false;
	}

	public function getUserdirAttribute() {
		return config('app.constants.path_user') . intval($this->userid) . '/';
	}

	public function getStatustextAttribute() {
		if($this->status == 1) {
			return '<span class="label label-success">Active</span>';
		}
		else {
			return '<span class="label label-danger">Inactive</span>';
		}
	}

	public function getFullnameAttribute() {
		return ucfirst($this->firstname.' '.$this->lastname);
	}

	public function getNewtokenAttribute() {
		return Hash::make(env('APP_KEY') . '-' . $this->userid);
	}

	public function scopeSearch($query, $value){
		if(!empty(trim($value))) {
			$value = trim($value);
			$query->Where(function($query) use ($value) {
				$query->Where('email', 'LIKE', "%$value%")
					  ->orWhere('firstname', 'LIKE', "%$value%")
					  ->orWhere('lastname', 'LIKE', "%$value%")
					  ->orWhere('age', 'LIKE', "%$value%")
					  ->orWhere('aboutme', 'LIKE', "%$value%")
					  ->orWhere('state', 'LIKE', "%$value%")
					  ->orWhere('interests', 'LIKE', "%$value%");
			});
		}
		return $query;
	}

	public function scopeFrontuser($query)
    {
        return $query->Where('usertype', 2);
    }

    public function scopeAdminuser($query)
    {
        return $query->Where('usertype', 1);
    }

    public function scopeNotsuperadmin($query)
    {
        return $query->where('issuperadmin', 0);
	}

	public function scopeActive($query){
		return $query->where('status', 1);
	}

    public function scopeExclude($query, $userids) {
        	if(is_array($userids)) {
        		$query->whereNotIn('userid', $userids);
        	}
        	else {
        		$query->where('userid', '!=', $userids);
        	}
    }

	public function series() {
		return $this->hasMany('App\Series', 'userid');
    }

    public function activitytokens() {
		return $this->hasMany('App\Activitytoken', 'userid');
    }

    public function followers() {
		return $this->hasMany('App\Followuser', 'userid');
    }

    public function notifications() {
		return $this->hasMany('App\Notification', 'userid');
    }
}
