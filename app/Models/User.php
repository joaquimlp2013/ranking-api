<?php

namespace app\Models;

class User extends Model
{
    protected string $table = 'user';

    protected array $fillable = [
        'name',
    ];

    public function getTableName()
    {
        return $this->table;
    }
}
