<?php

namespace App\Repositories\Item;

use App\Item;
use App\Repositories\AbstractRepository;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:58
 */
abstract class AbstractItem extends AbstractRepository
{
    public abstract function findByName(string $name): Item;
}