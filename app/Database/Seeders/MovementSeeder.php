<?php

namespace app\Database\Seeders;

class MovementSeeder
{
    /**
     * Insere os movimentos padrão caso ainda não existam na base.
     */
    public function run()
    {
        $movements = [
            ['name' => 'Deadlift'],
            ['name' => 'Back Squat'],
            ['name' => 'Bench Press'],
        ];

        foreach ($movements as $movementData) {
            $movementModel = new \app\Models\Movement();
            $hasMovement = $movementModel->has('name', $movementData['name']);
            if (!$hasMovement) {
                $movementModel->create($movementData);
            }
        }
    }
}
