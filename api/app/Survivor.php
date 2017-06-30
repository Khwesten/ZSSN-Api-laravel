<?php

namespace App;

/**
 * @SWG\Definition(
 *     definition="Survivor",
 *     required={"name", "gender", "age", "location"},
 *     type="object",
 *     @SWG\Property(type="string", property="name", example="Someone"),
 *     @SWG\Property(type="string", property="gender", example="M"),
 *     @SWG\Property(type="integer", property="age", example=20),
 *     @SWG\Property(type="string", example={"latitude":15.5242316, "longitude":-55.6175044}, property="location")
 * )
 */
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
