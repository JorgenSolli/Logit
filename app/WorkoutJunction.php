<?php

namespace App;

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
        'exercise_name',
        'set_nr',
        'reps',
        'weight',
    ];
}
