<?php

namespace app\Models;

class Movement extends Model
{
    protected string $table = 'movement';

    protected array $fillable = [
        'name',
    ];

    public function getTableName()
    {
        return $this->table;
    }
}
