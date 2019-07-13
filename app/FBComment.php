<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FBComment extends Model
{
	protected $table = 'facebook_comments';
	
    public $fillable = ['id', 'posts', 'facebook_account_id'];

    public function account() {
    	return $this->belongsTo('App\FBAccount');
    }
}
