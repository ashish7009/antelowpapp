<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Requestmedia extends Model {

	protected $table = 'request_medias';

	protected $fillable = ['seriesmediaid', 'userid', 'created_at', 'updated_at'];

	public $primaryKey = 'counterid';

	public $timestamps = true;

}
