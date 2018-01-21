<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class RoutineJunction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'routine_id', 
        'exercise_name',
        'goal_reps',
        'goal_sets',
        'goal_weight',
        'muscle_group',
        'media',
    ];
}
