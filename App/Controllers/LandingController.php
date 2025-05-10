<?php

namespace CarCatalog\App\Controllers;

use CarCatalog\App\Core\Connection;

class LandingController
{
    // Метод отображения главной страницы (уже существует)
    public function index(): void
    {
        // Подключает шаблон главной страницы
        include __DIR__ . '/../../views/index.php';
    }

    // *** Новый метод для обработки отправленной заявки ***
    public function submitRequest(): void
    {
        // Получаем данные из формы
        $name  = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';

        // Простая валидация наличия имени и телефона
        if (empty($name) || empty($phone)) {
            die('Имя и телефон обязательны.');
        }

        // Подключаемся к базе данных
        $pdo = Connection::make();

        // Создаём таблицу requests, если её нет
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS requests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // Вставляем новую заявку в таблицу
        $stmt = $pdo->prepare("INSERT INTO requests (name, phone) VALUES (:name, :phone)");
        $stmt->execute(['name' => $name, 'phone' => $phone]);

        // Уведомление пользователю (через сессию) и редирект на главную
        $_SESSION['flash'] = 'Ваша заявка успешно отправлена!';  // сообщение на один показ
        header('Location: /');
        exit;
    }
}
