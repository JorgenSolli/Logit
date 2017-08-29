<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
        'timezone',
        'unit',
        'recap',
        'share_workouts',
        'accept_friends',
        'strict_previous_exercise',
        'count_warmup_in_stats',
        'use_timer',
    ];
}
