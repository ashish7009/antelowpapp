<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Series;

class Seriesmedia extends Model {

	protected $table = 'seriesmedias';

	protected $primaryKey = 'seriesmediaid';

	protected $appends = ['filesdir', 'filedir', 'hasfile', 'filepath', 'thumbdir', 'hasthumb', 'thumbpath', 'statustext', 'publishdatetime', 'ispublished', 'publishdateair', 'publishdateday', 'hasurlthumb', 'urlthumbpath', 'workingurl'];

	public function scopeActive($query){
		return $query->where('seriesmedias.status', 1);
	}

	public function scopeAirtoday($query) {
		$now = Carbon::now();
		$tomorrow = Carbon::tomorrow();
		return $query->where('seriesmedias.publishdate', '>', $now)->where('publishdate', '<', $tomorrow);
	}

	public function scopeNoimmidiatepublish($query) {
		return $query->where('seriesmedias.immidiatepublish', 0);
	}

	public function scopeOfuser($query, $userid) {
		$userseriesids = Series::active()->ofuser($userid)->get()->lists('seriesid');
		return $query->whereIn('seriesmedias.seriesid', $userseriesids);	
	}

	public function scopeAiredinlasthour($query) {
		$now = Carbon::now();
		$lasthourstart = $now->copy()->addMinutes(-60);
		return $query->where('seriesmedias.publishdate', '>=', $lasthourstart)->where('publishdate', '<=', $now);
	}

	public function scopeSearch($query, $value){		
		$value = trim($value);
		if(!empty($value)) {
			$query->select('seriesmedias.*');
			$query->join('series', 'seriesmedias.seriesid','=', 'series.seriesid')->join('users', 'users.userid','=', 'series.userid');
			$query->where(function($query) use ($value) {
				$query->where('seriesmedias.title', 'LIKE', "%$value%")
					  ->orWhere('users.firstname', 'LIKE', "%$value%")
					  ->orWhere('users.lastname', 'LIKE', "%$value%");
			});
		}
		return $query;
	}

	public function scopeDesc($query) {
		return $query->orderBy('seriesmedias.seriesmediaid', 'desc');
	}

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
				if($diffInDays == 0) {
					$now = Carbon::now();
					$diffInHours = $now->diffInHours($publishdate);
					if($diffInHours > 0) {
	    				return 'You can see this in ' . $diffInHours . ' hour' . (($diffInHours > 1) ? 's' : '');		
	    			}
	    			else {
	    				$diffInMinutes = $now->diffInMinutes($publishdate);
	    				if($diffInMinutes > 0) {
	    					return 'You can see this in ' . $diffInMinutes . ' minute' . (($diffInMinutes > 1) ? 's' : '');			
	    				}
	    				else {
	    					$diffInSeconds = $now->diffInSeconds($publishdate);
	    					return 'You can see this in ' . $diffInSeconds . ' second' . (($diffInSeconds > 1) ? 's' : '');			
	    				}
	    			}
				}
				else {
					if($publishdate->minute == 0) {
						return 'You can see this on '.$publishdate->format('l \\a\t h A');
					}
					else {
						return 'You can see this on '.$publishdate->format('l \\a\t h A');
					}	
				}
			}
			else {
				return 'You can see this on ' . $publishdate->format('m\\\d');	
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
    		else if($this->thumbid != '') {
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
    		else if($this->thumbid != '') {
    			return 'https://i.vimeocdn.com/video/'.$this->thumbid.'_640x366.jpg';
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

    public function series() {
		return $this->belongsTo('App\Series', 'seriesid');
	}

	public function seriesmedialikesdislikes() {
		return $this->hasMany('App\Seriesmedialikedislike', 'seriesmediaid');
    }

    public function seriesmediaadminlikesdislikes() {
        return $this->hasMany('App\Seriesmediaadminlikedislike', 'seriesmediaid');
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

    public function updateProcessingStatus() {
        $lib = new \Vimeo\Vimeo(env('CLIENT_ID'), env('CLIENT_SECRET'));
		$lib->setToken(env('ACCESS_TOKEN'));

    	$video_response = $lib->request('/videos'.'/'.trim($this->videoid), [], 'GET');

        if( isset($video_response['status']) && 
            $video_response['status'] == 200 && 
            isset($video_response['body']['status']) && 
            strtolower($video_response['body']['status']) == 'available'&&
            isset($video_response['body']['pictures']) &&
            !is_null($video_response['body']['pictures']) && 
            isset($video_response['body']['duration']) &&
            intval($video_response['body']['duration']) > 0
        ) {

        	$this->isprocessing = 0;
            $array = explode('/', $video_response['body']['pictures']['uri']);
            $this->thumbid = $array[count($array) - 1];	
            $filepath = public_path($this->filesdir);
	        File::deleteDirectory($filepath);
        	if(intval($video_response['body']['duration']) > 60) {
        		$this->status = 2;
        	}
        	else {
        		$this->status = 1;
        	}

        	$this->update();
        }
    }
}