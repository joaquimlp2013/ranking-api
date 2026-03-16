<?php

namespace app\Services;

use app\Repositories\MovementRepository;
use app\Repositories\PersonalRecordRepository;

class RankingService
{
    protected MovementRepository $movementRepository;
    protected PersonalRecordRepository $recordRepository;

    /**
     * Construtor: inicializa os repositórios de movimentos e recordes.
     */
    public function __construct()
    {
        $this->movementRepository = new MovementRepository();
        $this->recordRepository = new PersonalRecordRepository();
    }

    /**
     * Retorna o ranking de um movimento pelo ID ou nome.
     * @param int|null $movementId
     * @param string|null $movementName
     * @return array
     * @throws \Exception
     */
    public function getRanking(?int $movementId, ?string $movementName): array
    {
        $movement = $this->resolveMovement($movementId, $movementName);

        if (!$movement) {
            throw new \Exception('Movement not found');
        }

        $records = $this->recordRepository->getRecordsByMovementId($movement['id']);

        $ranking = $this->buildRanking($records);

        return [
            'movement' => $movement['name'],
            'ranking' => $ranking
        ];
    }

    /**
     * Resolve o movimento pelo ID ou nome.
     * @param int|null $movementId
     * @param string|null $movementName
     * @return array|null
     * @throws \Exception
     */
    private function resolveMovement(?int $movementId, ?string $movementName)
    {
        if ($movementId) {
            return $this->movementRepository->findById($movementId);
        }

        if ($movementName) {
            return $this->movementRepository->findByName($movementName);
        }

        throw new \Exception('Movement parameter is required');
    }

    /**
     * Monta o ranking a partir dos records, ordenando e atribuindo posições.
     * @param array $records
     * @return array
     */
    private function buildRanking(array $records): array
    {
        $ranking = [];
        $lastValue = null;
        $position = 0;

        foreach ($records as $index => $record) {

            if ($record['value'] !== $lastValue) {
                $position = $index + 1;
            }

            $ranking[] = [
                'position' => $position,
                'user' => $record['user_name'],
                'record' => (float) $record['value'],
                'date' => $record['date']
            ];

            $lastValue = $record['value'];
        }

        return $ranking;
    }
}