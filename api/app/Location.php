<?php

namespace App;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 16:38
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