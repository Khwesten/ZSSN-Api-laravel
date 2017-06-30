<?php

namespace App\Repositories\Survivor;

use App\Location;
use App\ModelInterface;
use App\Survivor;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;

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
        $this->model->save();

        return $this->model;
    }

    public function delete(int $id)
    {
        return Survivor::destroy($id);
    }

    public function find(int $id)
    {
        $survivor = Survivor::findOrFail($id);

        return $survivor;
    }

    public function markAsInfected(int $survivorId)
    {
        $result = Survivor::findOrFail($survivorId)->update(['is_infected' => true]);

        return $result;
    }

    public function addItems(array $survivorItems)
    {
        return $this->model->survivorItems()->saveMany($survivorItems);
    }

    public function countAllSurvivors(): int
    {
        return DB::table('survivors')->count();
    }

    public function countInfectedSurvivors(): int
    {
        return DB::table('survivors')->where('is_infected', true)->count();
    }
}