<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 16:56
 */
class SurvivorItem extends ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity'
    ];

    /**
     * Get the item from survivorItem.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the survivor from survivorItem.
     */
    public function survivor()
    {
        return $this->belongsTo(Survivor::class);
    }
}