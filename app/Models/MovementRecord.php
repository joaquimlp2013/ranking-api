<?php

namespace app\Models;

class MovementRecord extends Model
{
    protected string $table = 'movement_record';

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
