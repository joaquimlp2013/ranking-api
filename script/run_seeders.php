<?php
// Script para rodar os seeders PHP

require __DIR__ . '/../vendor/autoload.php';

use app\Database\Seeders\DatabaseSeeder;

$seeder = new DatabaseSeeder();
$seeder->run();

echo "Seeders executados com sucesso!\n";
