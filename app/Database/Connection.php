<?php

namespace app\Database;

abstract class Connection
{
    protected static $instance;

    // Método para obter a instância da conexão PDO
    public static function getInstance()
    {
        // Se a instância ainda não foi criada, cria uma nova conexão
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            self::$instance = new \PDO(
                "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}",
                $config['username'],
                $config['password']
            );
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$instance;
    }
}
