<?php
namespace CarCatalog\App\Models;

use CarCatalog\App\Core\Connection;
use PDO;

class Request
{
    public static function all(): array
    {
        $stmt = Connection::make()
            ->query("SELECT id, name, phone, created_at FROM requests ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $name, string $phone): void
    {
        $pdo = Connection::make();
        $stmt = $pdo->prepare("
            INSERT INTO requests (name, phone, created_at)
            VALUES (:name, :phone, NOW())
        ");
        $stmt->execute(['name' => $name, 'phone' => $phone]);
    }
}
