<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FBAccount extends Model
{
	protected $table = 'facebook_accounts';
	
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

	public function comment() {
		return $this->hasOne('App\FBComment');
	}
}