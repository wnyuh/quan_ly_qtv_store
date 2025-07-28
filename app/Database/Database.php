<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Services\Logger;

class Database
{
    private static ?PDO $connection = null;
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $port = $_ENV['DB_PORT'] ?? 3306;
                $dbname = $_ENV['DB_NAME'] ?? 'store';
                $username = $_ENV['DB_USER'] ?? 'root';
                $password = $_ENV['DB_PASSWORD'] ?? '';
                
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                // Set MySQL specific options
                self::$connection->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
                self::$connection->exec("SET sql_mode = 'TRADITIONAL'");
            } catch (PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
    
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $logger = Logger::getInstance();
        $logger->info($sql);
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public static function execute(string $sql, array $params = []): bool
    {
        $logger = Logger::getInstance();
        $logger->info($sql);
        return self::query($sql, $params)->rowCount() > 0;
    }
}
