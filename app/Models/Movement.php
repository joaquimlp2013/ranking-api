<?php

namespace app\Models;

class Movement extends Model
{
    protected string $table = 'movement';

    protected array $fillable = [
        'name',
    ];

    /**
     * Retorna explicitamente o nome da tabela de movimentos.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table;
    }
}
