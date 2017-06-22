<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Survivor extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'age', 'gender', 'location', 'survivorItems', 'votesOfInfection', 'isInfected'
    ];

    /**
     * Get the location from survivor.
     *
     * @return Location
     */
    public function location(): Location
    {
        return $this->hasOne('App\Location');
    }

    /**
     * Get the survivorItems from survivor.
     *
     * @return array
     */
    public function survivorItems(): array
    {
        return $this->hasMany('App\SurvivorItem');
    }

    /**
     * Get the survivorItems from survivor.
     *
     * @return array
     */
    public function votesOfInfection(): array
    {
        return $this->belongsToMany('App\VoteOfInfection');
    }
}
