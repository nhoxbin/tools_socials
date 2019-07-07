<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $fillable = [
    	'provider_uid',
		'name',
		'access_token',
		'cookie',
		'is_active',
		'status',
		'user_id'
    ];

    public function user() {
		return $this->belongsTo('App\User');
	}
}
