<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Truefriend extends Model {

	protected $table = 'truefriend';

	protected $primaryKey = 'truefriend_id';

	

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


}
