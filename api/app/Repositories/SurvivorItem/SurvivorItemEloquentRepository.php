<?php

namespace App\Repositories\SurvivorItem;

use App\Survivor;
use App\SurvivorItem;
use App\VoteOfInfection;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:54
 */
class SurvivorItemEloquentRepository extends AbstractSurvivorItem
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
        // TODO: Implement delete() method.
    }

    public function find(int $id)
    {
        $survivor = Survivor::findOrFail($id);
        return $survivor;
    }

    public function addSurvivorItemsWithUser(array $survivorItems)
    {
        return SurvivorItem::insert($survivorItems);
    }

    public function addSurvivorItemWithUser()
    {
        return $this->model->save();
    }
}