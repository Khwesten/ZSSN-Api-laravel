<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 18:43
 */
class VoteOfInfection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survivor', 'infectedSurvivor'
    ];

    /**
     * Get the survivor from VoteOfInfection.
     *
     * @return Item
     */
    public function survivor(): Item {
        return $this->hasMany('App\Survivor');
    }

    /**
     * Get the infectedSurvivor from VoteOfInfection.
     *
     * @return Item
     */
    public function infectedSurvivor(): Item {
        return $this->hasMany('App\Survivor');
    }
}