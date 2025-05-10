<?php
namespace CarCatalog\App\Models;

use CarCatalog\App\Core\Connection;
use PDO;

class Car
{
    public static function getAll(): array
    {
        $pdo  = Connection::make();
        $sql = "
        SELECT
            c.*,
            (
              SELECT filename
              FROM car_images AS ci
              WHERE ci.car_id = c.id
                AND ci.is_main = 1
              LIMIT 1
            ) AS main_image
        FROM cars AS c
        ORDER BY c.id DESC
    ";
        return $pdo->query($sql)->fetchAll();
    }


    public static function getById(int $id): ?array
    {
        $pdo  = Connection::make();
        $stmt = $pdo->prepare('SELECT * FROM cars WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function getImages(int $id): array
    {
        $pdo = Connection::make();
        $stmt = $pdo->prepare("
        SELECT id, filename, is_main      -- ← добавили id
        FROM car_images
        WHERE car_id = :id
        ORDER BY is_main DESC, id ASC
        ");
        $stmt->execute(['id' => $id]);

        /*   ↓↓↓  главное - поставить глобальный префикс  */
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
