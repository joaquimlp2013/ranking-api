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

    /**
     * Inicializa a conexão compartilhada utilizada pelas operações do model.
     */
    public function __construct()
    {
        $this->conn = parent::getInstance();
    }

    /**
     * Resolve o nome padrão da tabela a partir do nome curto da classe.
     *
     * @return string
     */
    public function getTableName()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * Cria um novo registro utilizando apenas os campos liberados no model.
     *
     * @param array $data
     * @return string
     */
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

    /**
     * Busca o primeiro registro que corresponda exatamente ao nome informado.
     *
     * @param string $name
     * @return array|false
     */
    public function findByName(string $name)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE name = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception('Erro ao preparar statement: ' . $this->conn->errorInfo()[2]);
        }
        if (!$stmt->execute([$name])) {
            throw new \Exception('Erro ao executar query: ' . $stmt->errorInfo()[2]);
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um registro pelo identificador primário.
     *
     * @param mixed $id
     * @return array|false
     */
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

    /**
     * Verifica se existe ao menos um registro com o valor informado em um campo.
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function has(string $field, $value): bool
    {
        $sql = "SELECT 1 FROM {$this->getTableName()} WHERE {$field} = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception('Erro ao preparar statement: ' . $this->conn->errorInfo()[2]);
        }
        if (!$stmt->execute([$value])) {
            throw new \Exception('Erro ao executar query: ' . $stmt->errorInfo()[2]);
        }
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Retorna todos os registros da tabela associada ao model.
     *
     * @return array
     */
    public function get()
    {
        $sql = "SELECT * FROM {$this->getTableName()}";
        $stmt = $this->conn->query($sql);
        if (!$stmt) {
            throw new \Exception('Erro ao executar query: ' . $this->conn->errorInfo()[2]);
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza um registro pelo ID com os campos permitidos enviados.
     *
     * @param mixed $id
     * @param array $data
     * @return int
     */
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

    /**
     * Remove um registro pelo identificador informado.
     *
     * @param mixed $id
     * @return int
     */
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
