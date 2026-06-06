<?php

namespace App\Services;

use PDO;
use App\Helpers\Env;
use Exception;

class Database
{
    private static ?PDO $instance = null;
    private function __construct() {}
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host = Env::get('DB_HOST', '127.0.0.1');
            $port = Env::get('DB_PORT', '3306');
            $dbName = Env::get('DB_DATABASE', 'sales_funnel_crm');
            $username = Env::get('DB_USERNAME', 'root');
            $password = Env::get('DB_PASSWORD', '');
            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
            try {
                self::$instance = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (\PDOException $e) {
                throw new Exception("DB Error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
