<?php

namespace app\Repositories;

use app\Models\Movement;

class MovementRepository
{
    protected Movement $model;

    public function __construct()
    {
        $this->model = new Movement();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findByName(string $name)
    {
        return $this->model->findByName($name);
    }
}