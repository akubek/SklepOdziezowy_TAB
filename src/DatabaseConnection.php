<?php
class DatabaseConnection {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            
            $config = require_once '../config/database.php';
            
            $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']};";
            
            try {
                self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log("DB connection error: " . $e->getMessage());
                
                throw new Exception("Could not connect with database");
            }
        }

        return self::$pdo;
    }
}
