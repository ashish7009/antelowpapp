<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Emailtemplate extends Model {
	
	protected $table = 'emailtemplates';

	protected $primaryKey = 'emailtemplateid';

	protected $appends = ['statustext'];
	
	public function scopeActive($query){
		return $query->where('status', 1);
	}
	
	public function replaceDynamicData($core_array, $dynamic_array) {

		$core_array_global = [
			'#website#', 
			'#logo_path#',
			'#website_domain_name#',
			'#copyright_year#',
			'#base_url#'
		];
		$dynamic_array_global = [
			config('app.constants.website'),
			asset('images/logo1.png'),
			config('app.constants.websitedomainname'),
			date('Y'),
			url('/')
		];
		
		$core_array_final = array_merge($core_array_global, $core_array);
		$dynamic_array_final = array_merge($dynamic_array_global, $dynamic_array);

		$this->mailto = str_replace($core_array_final, $dynamic_array_final, $this->mailto);
		$this->mailtoname = str_replace($core_array_final, $dynamic_array_final, $this->mailtoname);
		$this->mailfrom = str_replace($core_array_final, $dynamic_array_final, $this->mailfrom);
		$this->mailfromname = str_replace($core_array_final, $dynamic_array_final, $this->mailfromname);
		$this->mailsubject = str_replace($core_array_final, $dynamic_array_final, $this->mailsubject);
		$this->mailbody = str_replace($core_array_final, $dynamic_array_final, $this->mailbody);
		$this->replyto = str_replace($core_array_final, $dynamic_array_final, $this->replyto);
		$this->replytoname = str_replace($core_array_final, $dynamic_array_final, $this->replytoname);
		$this->ccto = str_replace($core_array_final, $dynamic_array_final, $this->ccto);
		$this->cctoname = str_replace($core_array_final, $dynamic_array_final, $this->cctoname);
		$this->bccto = str_replace($core_array_final, $dynamic_array_final, $this->bccto);
		$this->bcctoname = str_replace($core_array_final, $dynamic_array_final, $this->bcctoname);
		
	}
	
	public function scopeSearch($query, $value){
		if(!empty(trim($value))) {
			$value = trim($value);
			return $query->where('caption', 'LIKE', "%$value%")
						 ->orWhere('mailto', 'LIKE', "%$value%")
						 ->orWhere('mailtoname', 'LIKE', "%$value%")
						 ->orWhere('mailfrom', 'LIKE', "%$value%")
						 ->orWhere('mailfromname', 'LIKE', "%$value%")
						 ->orWhere('mailsubject', 'LIKE', "%$value%")
						 ->orWhere('mailbody', 'LIKE', "%$value%")
						 ->orWhere('replyto', 'LIKE', "%$value%")
						 ->orWhere('replytoname', 'LIKE', "%$value%")
						 ->orWhere('ccto', 'LIKE', "%$value%")
						 ->orWhere('cctoname', 'LIKE', "%$value%")
						 ->orWhere('bccto', 'LIKE', "%$value%")
						 ->orWhere('bcctoname', 'LIKE', "%$value%");
		}
		return $query;
	}
	
	public function getStatustextAttribute() {
		if($this->status == 1) {
			return '<span class="label label-success">Active</span>';
		}
		else {
			return '<span class="label label-danger">Inactive</span>';
		}
	}
}
