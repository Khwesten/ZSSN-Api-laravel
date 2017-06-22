<?php

namespace App\Repositories\Survivor;

use App\Location;
use App\Survivor;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:54
 */
class SurvivorEloquentRepository implements SurvivorInterface
{

    public function create(array $data)
    {
        return Survivor::create($data);
    }

    public function report()
    {
        // TODO: Implement report() method.
    }

    public function tradeWith()
    {
        // TODO: Implement tradeWith() method.
    }

    public function markAsInfected(int $survivorId, int $infectedSurvivorId)
    {
        $user = User::find($id);
        $user->isInfected = true;

        return $user->save();
    }

    public function updateLocation(int $id, Location $location)
    {
        $user = User::find($id);
        $user->location = $location;

        return $user->save();
    }
}