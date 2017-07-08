<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'friend_with',
        'pending',
    ];
}
