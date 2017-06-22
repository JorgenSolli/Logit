<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'routine_name',
        'status',
    ];
}
