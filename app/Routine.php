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
        'active',
        'deleted',
        'pending',
        'sharer',
    ];

    /**
     * Get the related workout junction
     */
    public function junction()
    {
        return $this->hasMany('Logit\RoutineJunction');
    }
}
