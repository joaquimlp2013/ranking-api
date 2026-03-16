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
                http_response_code(400);
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
            http_response_code($e->getMessage() === 'Movement not found' ? 404 : 500);
            return [
                'status' => false,
                'message' => $e->getMessage() === 'Movement not found'
                    ? 'Movement not found.'
                    : 'Failed to retrieve ranking.'
            ];
        }
    }
}
