<?php

namespace app\Repository;

use app\Models\PersonalRecord;

class PersonalRecordRepository
{
	protected PersonalRecord $model;

	public function __construct()
	{
		$this->model = new PersonalRecord();
	}

	   /**
		* Retorna todos os registros de personal_record.
		*/
	   public function all()
	   {
		   return $this->model->get();
	   }

	   /**
		* Busca um registro pelo ID.
		* @param int $id
		* @return array|null
		*/
	   public function find($id)
	   {
		   return $this->model->find($id);
	   }

	   /**
		* Cria um novo registro.
		* @param array $data
		* @return int ID inserido
		*/
	   public function create(array $data)
	   {
		   return $this->model->create($data);
	   }

	   /**
		* Atualiza um registro existente.
		* @param int $id
		* @param array $data
		* @return int Quantidade de linhas afetadas
		*/
	   public function update($id, array $data)
	   {
		   return $this->model->update($id, $data);
	   }

	   /**
		* Deleta um registro pelo ID.
		* @param int $id
		* @return int Quantidade de linhas afetadas
		*/
	   public function delete($id)
	   {
		   return $this->model->delete($id);
	   }
}