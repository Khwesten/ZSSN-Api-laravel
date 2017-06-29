<?php

namespace App;

class Survivor extends ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'age', 'gender', 'is_infected'
    ];

    /**
     * Get the location from survivor.
     *
     * @return Location
     */
    public function location()
    {
        return $this->hasOne(Location::class);
    }

    /**
     * Get the survivorItems from survivor.
     *
     * @return array
     */
    public function survivorItems()
    {
        return $this->hasMany(SurvivorItem::class);
    }

    /**
     * Get the survivorItems from survivor.
     *
     * @return array
     */
    public function votesOfInfection(): array
    {
        return $this->hasMany(VoteOfInfection::class);
    }
}
