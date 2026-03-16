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

        foreach ($users as $userData) {
            $user = new \app\Models\User();
            $hasUser = $user->has('name', $userData['name']);
            if (!$hasUser) {
                $user->create($userData);
            }
        }
    }
}