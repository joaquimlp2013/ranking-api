<?php

namespace app\Database\Seeders;

class MovementSeeder
{
    public function run()
    {
        $movement = [
            ['name' => 'Deadlift'],
            ['name' => 'Back Squat'],
            ['name' => 'Bench Press'],
        ];

        foreach ($movement as $movementData) {
            $movementModel = new \app\Models\Movement();
            $hasMovement = $movementModel->has('name', $movementData['name']);
            if (!$hasMovement) {
                $movementModel->create($movementData);
            }
        }
    }
}