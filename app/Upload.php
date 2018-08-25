<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model {

	protected $table = 'series';

	protected $primaryKey = 'seriesid';

	protected $appends = ['ongoingtext', 'seriesdir', 'filedir', 'hasfile', 'filepath', 'statustext'];

	public function getOngoingtextAttribute() {
		if($this->isongoing == 1) {
			return '<span class="label label-success">Yes</span>';
		}
		else {
			return '<span class="label label-danger">No</span>';
		}
	}

	public function scopeOngoing($query){
		return $query->where('isongoing', 1);
	}

	public function getSeriesdirAttribute() {
		return config('app.constants.path_series') . intval($this->seriesid) . '/';
	}

	public function getFiledirAttribute() {
		return $this->seriesdir . config('app.constants.dir_series_files');
	}

	public function getHasfileAttribute() {
		$filename = trim($this->filename);
		if($filename != '' && file_exists(public_path() . $this->filedir . $filename)) {
			return true;
		}
		return false;
	}

	public function getFilepathAttribute() {
		$filename = trim($this->filename);
		if($this->hasfile) {
			return asset($this->filedir . $filename);
		}
		else {
			return asset('images/no-image.png');
		}
	}

	public function getStatustextAttribute() {
		if($this->status == 1) {
			return '<span class="label label-success">Active</span>';
		}
		else if($this->status == 2) {
			return '<span class="label label-danger">Inactive</span>';
		}
	}

	public function scopeOfuser($query, $userid){
		return $query->where('userid', $userid);
	}

	public function scopeActive($query){
		return $query->where('status', 1);
	}

	public function scopeSearch($query, $value){		
		$value = trim($value);
		if(!empty($value)) {
			$query->Where(function($query) use ($value) {
				$query->Where('title', 'LIKE', "%$value%")
					  ->orWhere('slug', 'LIKE', "%$value%")
					  ->orWhere('description', 'LIKE', "%$value%");
			});
		}
		return $query;
	}

	public function user() {
		return $this->belongsTo('App\User', 'userid');
	}

	public function seriesmedias() {
		return $this->hasMany('App\Seriesmedia', 'seriesid');
    }

    public function seriesmediacomments() {
    	return $this->hasManyThrough('App\Seriesmediacomment', 'App\Seriesmedia', 'seriesid', 'seriesmediaid');
    }

    public function seriesmedialikesdislikes() {
    	return $this->hasManyThrough('App\Seriesmedialikedislike', 'App\Seriesmedia', 'seriesid', 'seriesmediaid');
    }

}
