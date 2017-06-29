<?php

namespace App\Repositories\Item;

use App\Item;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:54
 */
class ItemEloquentRepository extends AbstractItem
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
        $survivor = Item::findOrFail($id);

        return $survivor;
    }

    public function findByName(string $name): Item
    {
        return Item::where('name', $name)->first();
    }
}