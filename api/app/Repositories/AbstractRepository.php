<?php

namespace App\Repositories;

use App\ModelInterface;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 24/06/2017
 * Time: 13:45
 */
abstract class AbstractRepository /*implements RepositoryInterface*/
{
    protected $data;
    protected $model;

    public abstract function create();

    public abstract function update();

    public abstract function delete(int $id);

    public abstract function find(int $id);

    public function setModel(ModelInterface $model) {
        $this->model = $model;
    }
}