<?php

namespace App\Repositories\Survivor;

use App\Location;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:58
 */
interface SurvivorInterface
{
    public function create(array $data);
    public function report();
    public function tradeWith();
    public function markAsInfected(int $survivorId, int $infectedSurvivorId);
    public function updateLocation(int $id, Location $location);
}