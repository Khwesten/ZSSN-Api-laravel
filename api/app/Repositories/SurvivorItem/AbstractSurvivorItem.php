<?php

namespace App\Repositories\SurvivorItem;

use App\Repositories\AbstractRepository;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:58
 */
abstract class AbstractSurvivorItem extends AbstractRepository
{
    public abstract function addSurvivorItemsWithUser(array $survivorItems);
}