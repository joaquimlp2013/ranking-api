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

    public function getTableName()
    {
        return $this->table;
    }
}
