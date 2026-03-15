<?php

use app\Http\Controllers\RankingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
return [
    'GET' => [
        // Define a rota para obter o ranking
        '/api/ranking' => [RankingController::class, 'getRanking']
    ]
];
