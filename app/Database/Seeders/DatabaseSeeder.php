<?php

namespace app\Database\Seeders;

class DatabaseSeeder
{
    public function run()
    {
        (new UserSeeder())->run();
        (new MovementSeeder())->run();
        (new PersonalRecordSeeder())->run();
    }
}