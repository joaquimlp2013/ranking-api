<?php

namespace app\Http\Controllers;

class RankingController
{
    public function getRanking()
    {
        try {
            // Simula dados de ranking
            $ranking = [
                ['name' => 'Alice', 'score' => 100],
                ['name' => 'Bob', 'score' => 90],
                ['name' => 'Charlie', 'score' => 80],
            ];

            return [
                'error' => false,
                'data' => $ranking
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Failed to retrieve ranking'
            ];
        }
        
    }
}
