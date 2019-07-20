<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FBComment extends Model
{
	protected $table = 'facebook_comments';
	public $incrementing = false;
	
    public $fillable = ['provider_uid', 'comments', 'type'];

    public function account() {
    	return $this->belongsTo('App\FBAccount');
    }
}
