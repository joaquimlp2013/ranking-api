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
