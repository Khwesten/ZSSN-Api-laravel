<?php

namespace App\Repositories\Survivor;

use App\Location;
use App\ModelInterface;
use App\Survivor;
use Illuminate\Foundation\Auth\User;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:54
 */
class SurvivorEloquentRepository extends AbstractSurvivor
{
    public function create()
    {
        $this->model->save();

        return $this->model;
    }

    public function update()
    {
        // TODO: Implement update() method.
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

    public function report()
    {
        // TODO: Implement report() method.
    }

    public function tradeWith()
    {
        // TODO: Implement tradeWith() method.
    }

    public function markAsInfected(int $survivorId)
    {
        $result = Survivor::findOrFail($survivorId)->update(['is_infected' => true]);

        return $result;
    }

    public function addItens(array $survivorItems)
    {
        return $this->model->survivorItems()->saveMany($survivorItems);
    }
}