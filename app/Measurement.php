<?php

namespace Logit;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'weight',
        'body_fat',
        'neck',
        'shoulders',
        'arms',
        'chest',
        'waist',
        'forearms',
        'calves',
        'thighs',
        'hips',
        'date',
    ];
}
