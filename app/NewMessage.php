<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class NewMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
        'title',
        'type',
        'html',
        'confirmButtonText',
    ];
}
