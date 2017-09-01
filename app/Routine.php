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

    /**
     * Get the related workout junction
     */
    public function junction()
    {
        return $this->hasMany('Logit\RoutineJunction');
    }
}
