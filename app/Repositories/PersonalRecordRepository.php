<?php

namespace app\Repositories;

use app\Database\Connection;

class PersonalRecordRepository
{
    protected $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function getRecordsByMovementId(int $movementId)
    {
        $sql = "
            SELECT 
                u.name AS user_name,
                pr.value,
                pr.date
            FROM personal_record pr
            JOIN user u ON u.id = pr.user_id
            WHERE pr.movement_id = :movement_id
            ORDER BY pr.value DESC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'movement_id' => $movementId
        ]);

        return $stmt->fetchAll();
    }
}