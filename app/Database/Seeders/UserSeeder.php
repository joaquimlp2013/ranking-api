<?php

namespace app\Database\Seeders;

class UserSeeder
{
    public function run()
    {
        $users = [
            ['name' => 'João'],
            ['name' => 'José'],
            ['name' => 'Pualo'],
        ];

        $user = new \app\Models\User();

        foreach ($users as $userData) {
            $hasUser = $user->has('name', $userData['name']);
            if (!$hasUser) {
                $user->create($userData);
            }
        }
    }
}