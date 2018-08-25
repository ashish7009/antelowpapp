<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Seriesmedia2 extends Model {

	protected $table = 'seriesmedias';

	protected $primaryKey = 'seriesmediaid';

	protected $appends = ['filesdir', 'filedir', 'hasfile', 'filepath', 'thumbdir', 'hasthumb', 'thumbpath', 'statustext', 'publishdatetime', 'ispublished', 'publishdateair', 'publishdateday', 'hasurlthumb', 'urlthumbpath', 'workingurl'];

	public function getFilesdirAttribute() {
		return config('app.constants.path_series') . intval($this->seriesid) . '/' . config('app.constants.dir_medias') . intval($this->seriesmediaid) . '/';
	}

	public function getFiledirAttribute() {
		return $this->filesdir . config('app.constants.dir_file');
	}

	public function getHasfileAttribute() {
		$filename = trim($this->filename);
		if($this->isfile && $filename != '' && file_exists(public_path() . $this->filedir . $filename)) {
			return true;
		}
		return false;
	}

	public function getFilepathAttribute() {
		if($this->hasfile) {
			$filename = trim($this->filename);
			return asset($this->filedir . $filename);
		}
		else {
			return asset('images/no-image.png');
		}
	}

	public function getThumbdirAttribute() {
		return $this->filesdir . config('app.constants.dir_thumb');
	}

	public function getHasthumbAttribute() {
		$filethumbname = trim($this->filethumbname);
		if($this->isfile && $filethumbname != '' && file_exists(public_path() . $this->thumbdir . $filethumbname)) {
			return true;
		}
		return false;
	}

	public function getThumbpathAttribute() {
		if($this->hasthumb) {
			$filethumbname = trim($this->filethumbname);
			return asset($this->thumbdir . $filethumbname);
		}
		else {
			return asset('images/no-image.png');
		}
	}

	public function getStatustextAttribute() {
		if($this->status == 0) {
			return '<span class="label label-warning">Inactive</span>';
		}
		else {
			return '<span class="label label-success">Active</span>';
		}
	}

    public function getPublishdateairAttribute() {
    	if(!$this->ispublished) {
    		$today = Carbon::today();
    		$publishdate = Carbon::createFromFormat('Y-m-d H:i:s', $this->publishdate);
    		$publishdate->setTime(0, 0, 0);
			$diffInDays = $today->diffInDays($publishdate);
			$publishdate = Carbon::createFromFormat('Y-m-d H:i:s', $this->publishdate);
			if($diffInDays < 7) {
				if($publishdate->minute == 0) {
					return 'Will air '.$publishdate->format('l \\@ h A');
				}
				else {
					return 'Will air '.$publishdate->format('l \\@ h:i A');
				}
			}
			else {
				return 'Will air on ' . $publishdate->format('m\\\d');	
			}
    	}
    	return '';
    }

    public function getPublishdatedayAttribute() {
    	if(!$this->ispublished) {
    		$today = Carbon::today();
    		$publishdate = Carbon::createFromFormat('Y-m-d H:i:s', $this->publishdate);
    		$publishdate->setTime(0, 0, 0);
			$diffInDays = $today->diffInDays($publishdate);
			if($diffInDays < 7) {
				return strtolower($publishdate->format('l'));
			}
    	}
    	return '';
    }

    public function getIspublishedAttribute() {
    	if(!$this->immidiatepublish) {
	    	$publishdate = Carbon::createFromFormat('Y-m-d H:i:s', $this->publishdate);
    		return $publishdate->isPast();
	    }
	    return true;
    }

    public function getHasurlthumbAttribute() {
    	if(!$this->isfile) {
    		if(strpos($this->fileurl, 'www.youtube.com/embed/') !== false) {
    			return true;
    		}
    		else if(strpos($this->fileurl, 'youtu.be/') !== false) {
    			return true;
    		}
    		else if(strpos($this->fileurl, 'youtube.com/watch?v=') !== false) {
    			return true;
    		}
    	}
    	return false;
    }

    public function getUrlthumbpathAttribute() {
    	if($this->hasurlthumb) {
    		if(strpos($this->fileurl, 'www.youtube.com/embed/') !== false) {
    			$array = explode('www.youtube.com/embed/', $this->fileurl);
    			$videoid = $array[1];
    			return 'http://img.youtube.com/vi/'.$videoid.'/0.jpg';
    		}
    		else if(strpos($this->fileurl, 'youtu.be/') !== false) {
    			$array = explode('youtu.be/', $this->fileurl);
    			$videoid = $array[1];
    			return 'http://img.youtube.com/vi/'.$videoid.'/0.jpg';
    		}
    		else if(strpos($this->fileurl, 'youtube.com/watch?v=') !== false) {
    			$array = explode('youtube.com/watch?v=', $this->fileurl);
    			$videoid = $array[1];
    			if(strpos($videoid, '&') !== false) {
    				$array = explode('&', $videoid);
    				$videoid = $array[0];
    			}
    			return 'http://img.youtube.com/vi/'.$videoid.'/0.jpg';
    		}
    	}
    	return false;
    }

    public function getWorkingurlAttribute() {
    	if(!$this->isfile) {
    		if(strpos($this->fileurl, 'youtu.be/') !== false) {
    			$array = explode('youtu.be/', $this->fileurl);
    			$videoid = $array[1];
    			return 'http://www.youtube.com/embed/'.$videoid;
    		}
    		else if(strpos($this->fileurl, 'youtube.com/watch?v=') !== false) {
    			$array = explode('youtube.com/watch?v=', $this->fileurl);
    			$videoid = $array[1];
    			if(strpos($videoid, '&') !== false) {
    				$array = explode('&', $videoid);
    				$videoid = $array[0];
    			}
    			return 'http://www.youtube.com/embed/'.$videoid;
    		}
    		else if(strpos($this->fileurl, 'vimeo.com/') !== false && strpos($this->fileurl, 'player.vimeo.com/') === false) {
    			$array = explode('vimeo.com/', $this->fileurl);
    			$videoid = $array[1];
    			return 'https://player.vimeo.com/video/'.$videoid;
    		}
    		return $this->fileurl;
    	}
    	return '';
    }

	public function scopeActive($query){
		return $query->where('seriesmedias.status', 1);
	}

	public function scopeSearch($query, $value){		
		$value = trim($value);
		if(!empty($value)) {
			$query->join('series', 'series.seriesid', '=', 'seriesmedias.seriesid');
			$query->Where(function($query) use ($value) {
				$query->Where('seriesmedias.title', 'LIKE', "%$value%")
					  ->orWhere('seriesmedias.fileurl', 'LIKE', "%$value%")
					  ->orWhere('seriesmedias.description', 'LIKE', "%$value%")
					  ->orWhere('series.title', 'LIKE', "%$value%");
			});
			$query->orderBy('seriesmedias.created_at', 'desc');
		}
		return $query;
	}

	public function series() {
		return $this->belongsTo('App\Series', 'seriesid');
	}

	public function seriesmedialikesdislikes() {
		return $this->hasMany('App\Seriesmedialikedislike', 'seriesmediaid');
    }

	public function seriesmediacomments() {
		return $this->hasMany('App\Seriesmediacomment', 'seriesmediaid');
    }

	public function seriesmediaclicks() {
		return $this->hasMany('App\Mediaclick', 'seriesmediaid');
    }

    public function getPublishdatetimeAttribute() {
    	if(!$this->immidiatepublish) {
    		return date('Y-m-d H:i', strtotime($this->publishdate));
    	}
    	return '';
    }

}
