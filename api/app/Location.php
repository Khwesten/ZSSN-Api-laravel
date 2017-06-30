<?php

namespace App;

/**
 * @SWG\Definition(
 *     definition="Location",
 *     required={"name", "gender", "age", "location"},
 *     type="object",
 *     @SWG\Property(type="string", property="latitude", example="15.5242316"),
 *     @SWG\Property(type="string", property="longitude", example="-55.6175044")
 * )
 */
class Location extends ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'latitude', 'longitude'
    ];

    /**
     * Get the survivor that owns the locaiton.
     */
    public function survivor()
    {
        return $this->belongsTo(Survivor::class);
    }
}