<?php

namespace app\Models;

class PersonalRecord extends Model
{
    protected string $table = 'personal_record';

    protected array $fillable = [
        'user_id',
        'movement_id',
        'value',
        'date',
    ];

    /**
     * Retorna explicitamente o nome da tabela de recordes pessoais.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table;
    }
}
