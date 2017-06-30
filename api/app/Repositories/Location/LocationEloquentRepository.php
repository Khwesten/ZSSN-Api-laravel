<?php

namespace App\Repositories\Location;

use App\Location;
use App\Survivor;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:54
 */
class LocationEloquentRepository extends AbstractLocation
{
    public function create()
    {
        $this->model->save();
        return $this->model;
    }

    public function update()
    {
        $this->model->save();
        return $this->model;
    }

    public function delete(int $id)
    {
        return Location::destroy($id);
    }

    public function find(int $id)
    {
        return Location::findOrFail($id);
    }

    public function findBySurvivorId(int $survivorId)
    {
        $location = Location::where('survivor_id', $survivorId)->first();

        return $location;
    }
}