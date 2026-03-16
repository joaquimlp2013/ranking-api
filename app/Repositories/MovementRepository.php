<?php

namespace app\Repositories;

use app\Models\Movement;

class MovementRepository
{
    protected Movement $model;

    /**
     * Instancia o model de movimentos usado nas consultas do repositório.
     */
    public function __construct()
    {
        $this->model = new Movement();
    }

    /**
     * Busca um movimento pelo ID.
     *
     * @param int $id
     * @return array|false
     */
    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Busca um movimento pelo nome.
     *
     * @param string $name
     * @return array|false
     */
    public function findByName(string $name)
    {
        return $this->model->findByName($name);
    }
}
