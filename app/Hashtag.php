<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model {

	protected $table = 'hashtags';

	protected $fillable = ['hashtag_id', 'hashtag_content', 'counter', 'created_at', 'updated_at', 'isActive'];

	public $primaryKey = 'hashtag_id';

	public $timestamps = true;

}