<?php

namespace app\Database\Seeders;

class DatabaseSeeder
{
    /**
     * Executa os seeders principais na ordem necessária para popular a base.
     */
    public function run()
    {
        (new UserSeeder())->run();
        (new MovementSeeder())->run();
        (new PersonalRecordSeeder())->run();
    }
}
