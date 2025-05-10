<?php

namespace CarCatalog\App\Controllers;

use CarCatalog\App\Core\Connection;

class AdminController
{
    public function index(): void
    {
        include __DIR__ . '/../../views/admin/index.php';
    }

    // *** Новый метод: вывод списка заявок ***
    public function requests(): void
    {
        // Генерируем CSRF-токен для форм удаления
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Получаем все заявки из БД
        $pdo = Connection::make();
        $stmt = $pdo->query("SELECT * FROM requests ORDER BY id DESC");
        $requests = $stmt ? $stmt->fetchAll() : [];

        // Подключаем шаблон со списком заявок, передав данные
        include __DIR__ . '/../../views/admin/requests.php';
    }

    // *** Новый метод: удаление одной заявки по ID ***
    public function deleteRequest(int $id): void
    {
        $this->checkCsrf();  // проверка CSRF-токена

        $pdo = Connection::make();
        $stmt = $pdo->prepare("DELETE FROM requests WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // После удаления возвращаемся на страницу заявок
        header('Location: /admin/requests');
        exit;
    }

    // Вспомогательный метод проверки CSRF-токена (скопирован из CatalogController)
    private function checkCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            die('Invalid CSRF token');
        }
    }
}
