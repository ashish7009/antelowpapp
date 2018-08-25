<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificationlog extends Model
{
    protected $table = 'notificationlogs';
    protected $fillable = [ 'title', 'message','userid','status'];
    protected $primaryKey = 'notificationlog_id';
    public $timestamps = false;
}
