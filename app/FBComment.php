<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FBComment extends Model
{
	protected $table = 'facebook_comments';
	protected $primaryKey = ['provider_uid', 'type'];
	public $incrementing = false;
	
    public $fillable = ['provider_uid', 'comments', 'type'];

    public function account() {
    	return $this->belongsTo('App\FBAccount');
    }

    protected function setKeysForSaveQuery(Builder $query) {
        return $query->where('provider_uid', $this->getAttribute('provider_uid'))
            ->where('type', $this->getAttribute('type'));
    }
}
