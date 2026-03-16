<?php

namespace app\Http\Controllers;

class RankingController
{
    /**
     * Busca os parâmetros da requisição e retorna o ranking do movimento informado.
     *
     * @return array
     */
    public function getRanking()
    {
        try {
            $movementId = isset($_GET['movement_id']) ? (int) $_GET['movement_id'] : null;
            $movementName = isset($_GET['movement']) ? trim($_GET['movement']) : null;

            if ($movementId === null && empty($movementName)) {
                return [
                    'status' => false,
                    'message' => 'It is required to provide movement_id or movement.'
                ];
            }

            $rankingService = new \app\Services\RankingService();
            $ranking = $rankingService->getRanking($movementId, $movementName);
            return [
                "status" => true,
                "data" => $ranking
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to retrieve ranking'
            ];
        }
    }
}
