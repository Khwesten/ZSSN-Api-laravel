<?php

namespace App\Repositories\Survivor;

use App\Repositories\AbstractRepository;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:58
 */
abstract class AbstractSurvivor extends AbstractRepository
{
    public abstract function markAsInfected(int $survivorId);

    public abstract function addItems(array $survivorItems);

    public abstract function countAllSurvivors(): int;

    public abstract function countInfectedSurvivors(): int;
}