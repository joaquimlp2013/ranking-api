<?php

namespace app\Repository;

use app\Models\User;

class UserRepository
{
	protected User $model;

	public function __construct()
	{
		$this->model = new User();
	}

	/**
	 * Retorna todos os usuários.
	 */
	public function all()
	{
		return $this->model->get();
	}

	/**
	 * Busca um usuário pelo ID.
	 * @param int $id
	 * @return array|null
	 */
	public function find($id)
	{
		return $this->model->find($id);
	}

	/**
	 * Cria um novo usuário.
	 * @param array $data
	 * @return int ID inserido
	 */
	public function create(array $data)
	{
		return $this->model->create($data);
	}

	/**
	 * Atualiza um usuário existente.
	 * @param int $id
	 * @param array $data
	 * @return int Quantidade de linhas afetadas
	 */
	public function update($id, array $data)
	{
		return $this->model->update($id, $data);
	}

	/**
	 * Deleta um usuário pelo ID.
	 * @param int $id
	 * @return int Quantidade de linhas afetadas
	 */
	public function delete($id)
	{
		return $this->model->delete($id);
	}
}