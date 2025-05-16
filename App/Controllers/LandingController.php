<?php

namespace CarCatalog\App\Controllers;

use CarCatalog\App\Core\Connection;

class LandingController
{
    public function index(): void
    {
        include __DIR__ . '/../../views/index.php';
    }

    public function submitRequest(): void
    {
        $name  = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if (empty($name) || empty($phone)) {
            die('Имя и телефон обязательны.');
        }

        $pdo = Connection::make();

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS requests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $stmt = $pdo->prepare("INSERT INTO requests (name, phone) VALUES (:name, :phone)");
        $stmt->execute(['name' => $name, 'phone' => $phone]);

        $_SESSION['flash'] = 'Ваша заявка успешно отправлена!';
        header('Location: /');
        exit;
    }
}
