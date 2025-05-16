<?php

namespace CarCatalog\App\Controllers;

use CarCatalog\App\Core\Connection;

class AdminController
{
    public function index(): void
    {
        include __DIR__ . '/../../views/admin/index.php';
    }

    public function requests(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $pdo = Connection::make();
        $stmt = $pdo->query("SELECT * FROM requests ORDER BY id DESC");
        $requests = $stmt ? $stmt->fetchAll() : [];

        include __DIR__ . '/../../views/admin/requests.php';
    }

    public function deleteRequest(int $id): void
    {
        $this->checkCsrf();

        $pdo = Connection::make();
        $stmt = $pdo->prepare("DELETE FROM requests WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header('Location: /admin/requests');
        exit;
    }

    private function checkCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            die('Invalid CSRF token');
        }
    }
}
