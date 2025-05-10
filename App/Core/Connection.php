<?php
namespace CarCatalog\App\Core;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $pdo = null;

    public static function make(): PDO
    {
        if (self::$pdo === null) {
            // вот здесь — единый require конфига
            $config = require __DIR__ . '/../Config/config.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";
            try {
                self::$pdo = new PDO(
                    $dsn,
                    $config['user'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die('DB connection error: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
