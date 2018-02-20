<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class LatestActivity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
		'activity_type',
    	'activity',
    ];
}
