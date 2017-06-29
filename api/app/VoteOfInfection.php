<?php

namespace App;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 18:43
 */
class VoteOfInfection extends ModelInterface
{
    /**
     * Get the survivor from VoteOfInfection.
     *
     * @return Item
     */
    public function survivor() {
        return $this->belongsTo(Survivor::class);
    }

    /**
     * Get the infectedSurvivor from VoteOfInfection.
     *
     * @return Item
     */
    public function infectedSurvivor() {
        return $this->belongsTo(Survivor::class, 'infected_survivor_id');
    }
}