<?php

namespace app\Http\Controllers;

class RankingController
{
    public function getRanking()
    {
        try {
            $movementId = isset($_GET['movement_id']) ? (int) $_GET['movement_id'] : null;
            $movementName = $_GET['movement'] ?? null;

            $rankingService = new \app\Services\RankingService();
            $ranking = $rankingService->getRanking($movementId, $movementName);
            var_dump($ranking);
            return [];
        } catch (\Exception $e) {

            echo json_encode([
                'error' => true,
                'message' => $e->getMessage()
            ]);
            return [
                'error' => true,
                'message' => 'Failed to retrieve ranking'
            ];
        }
    }
}
