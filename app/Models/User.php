<?php

namespace app\Models;

class User extends Model
{
    protected string $table = 'user';

    protected array $fillable = [
        'name',
    ];

    /**
     * Retorna explicitamente o nome da tabela de usuários.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table;
    }
}
