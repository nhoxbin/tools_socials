<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FBAccount extends Model
{
	protected $table = 'facebook_accounts';
	protected $primaryKey = 'provider_uid';
	public $incrementing = false;
	
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
		return $this->hasMany('App\FBComment');
	}
}
