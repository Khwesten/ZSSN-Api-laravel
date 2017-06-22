<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 16:56
 */
class SurvivorItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'item'
    ];

    /**
     * Get the item from survivorItem.
     *
     * @return Item
     */
    public function item(): Item {
        return $this->belongsToMany('App\Item');
    }
}