<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class WorkoutJunction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'routine_id', 
        'workout_id', 
        'is_warmup',
        'exercise_name',
        'set_nr',
        'reps',
        'weight',
    ];
}
