<?php

namespace app\Repositories;

use app\Database\Connection;

class PersonalRecordRepository
{
    protected $connection;

    /**
     * Inicializa a conexão PDO usada nas consultas de recordes.
     */
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /**
     * Busca o melhor recorde pessoal de cada usuário para um movimento.
     *
     * @param int $movementId
     * @return array
     */
    public function getRecordsByMovementId(int $movementId)
    {
        $sql = "
            SELECT
                ranked.user_name,
                ranked.value,
                ranked.date
            FROM (
                SELECT
                    u.name AS user_name,
                    pr.value,
                    pr.date,
                    ROW_NUMBER() OVER (
                        PARTITION BY pr.user_id
                        ORDER BY pr.value DESC, pr.date ASC, pr.id ASC
                    ) AS row_num
                FROM personal_record pr
                JOIN user u ON u.id = pr.user_id
                WHERE pr.movement_id = :movement_id
            ) AS ranked
            WHERE ranked.row_num = 1
            ORDER BY ranked.value DESC, ranked.date ASC, ranked.user_name ASC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'movement_id' => $movementId
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
