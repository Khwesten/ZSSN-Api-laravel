<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 16:38
 */
class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'latitude', 'longitude'
    ];
}