<?php

namespace App\Repositories\SurvivorItem;

use App\Survivor;
use App\SurvivorItem;
use App\VoteOfInfection;
use Illuminate\Support\Facades\DB;

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
        return SurvivorItem::destroy($id);
    }

    public function find(int $id)
    {
        $survivor = Survivor::find($id);
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

    public function countPointsFromInfectedSurvivors(): int
    {
        $result = $users = DB::table('survivor_items')
            ->join('survivors', 'survivors.id', '=', 'survivor_items.survivor_id')
            ->select(DB::raw('sum(survivor_items.quantity) as count'))
            ->where('survivors.is_infected', true)
            ->first();

        $quantity = $result->count ?? 0;

        return $quantity;
    }

    public function getAmountOfItemsByKind(): array
    {
        $result = $users = DB::table('survivor_items')
            ->join('items', 'items.id', '=', 'survivor_items.item_id')
            ->select(DB::raw('DISTINCT(items.name) as name, SUM(survivor_items.quantity) as quantity'))
            ->groupBy('items.name')
            ->get();

        return $result->toArray();
    }
}