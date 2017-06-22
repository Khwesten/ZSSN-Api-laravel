<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 16:50
 */
class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'points', 'survivorItems'
    ];

    /**
     * Get the survivorItems from item.
     *
     * @return array
     */

    public function survivorItems(): array
    {
        return $this->belongsToMany('App\SurvivorItem');
    }
}