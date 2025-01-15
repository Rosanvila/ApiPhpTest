<?php

namespace App\Database;

use PDO;
use PDOException;

final class DBconnect
{
    private static DBconnect $instance;
    private PDO $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../config/config.php';
        try {
            $this->connection = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public static function getInstance(): DBconnect
    {
        if (!isset(self::$instance)) {
            self::$instance = new DBconnect();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function testConnection(): bool
    {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
