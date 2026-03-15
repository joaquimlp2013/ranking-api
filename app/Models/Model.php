<?php

namespace app\Models;

use app\Database\Connection;

abstract class Model extends Connection
{
    
    /**
     * Campos permitidos para inserção/atualização
     * Defina nas models filhas.
     * @var array
     */
    protected array $fillable = [];
    protected $conn;

    public function __construct()
    {
        $this->conn = parent::getInstance();
    }

    public function getTableName()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function create(array $data)
    {
        // Filtra apenas os campos permitidos
        $fields = array_intersect_key($data, array_flip($this->fillable));
        if (empty($fields)) {
            throw new \InvalidArgumentException('Nenhum campo válido para inserção.');
        }

        $columns = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $sql = "INSERT INTO {$this->getTableName()} ($columns) VALUES ($placeholders)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new \Exception('Erro ao preparar statement: ' . $this->conn->errorInfo()[2]);
        }
        if (!$stmt->execute(array_values($fields))) {
            throw new \Exception('Erro ao executar insert: ' . $stmt->errorInfo()[2]);
        }

        return $this->conn->lastInsertId();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception('Erro ao preparar statement: ' . $this->conn->errorInfo()[2]);
        }
        if (!$stmt->execute([$id])) {
            throw new \Exception('Erro ao executar query: ' . $stmt->errorInfo()[2]);
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function get()
    {
        $sql = "SELECT * FROM {$this->getTableName()}";
        $stmt = $this->conn->query($sql);
        if (!$stmt) {
            throw new \Exception('Erro ao executar query: ' . $this->conn->errorInfo()[2]);
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update($id, array $data)
    {
        // Filtra apenas os campos permitidos
        $fields = array_intersect_key($data, array_flip($this->fillable));
        if (empty($fields)) {
            throw new \InvalidArgumentException('Nenhum campo válido para atualização.');
        }

        $set = implode(', ', array_map(fn($key) => "$key = ?", array_keys($fields)));
        $sql = "UPDATE {$this->getTableName()} SET $set WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception('Erro ao preparar statement: ' . $this->conn->errorInfo()[2]);
        }
        if (!$stmt->execute([...array_values($fields), $id])) {
            throw new \Exception('Erro ao executar update: ' . $stmt->errorInfo()[2]);
        }

        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->getTableName()} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception('Erro ao preparar statement: ' . $this->conn->errorInfo()[2]);
        }
        if (!$stmt->execute([$id])) {
            throw new \Exception('Erro ao executar delete: ' . $stmt->errorInfo()[2]);
        }

        return $stmt->rowCount();   
    }
}