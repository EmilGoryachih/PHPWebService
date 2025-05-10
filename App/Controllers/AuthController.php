<?php
namespace CarCatalog\App\Controllers;

class AuthController
{
    public function login(): void
    {
        // Если админ уже вошёл, перенаправляем в админ-панель
        if (!empty($_SESSION['is_admin'])) {
            header('Location: /admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Обработка отправки формы входа
            $login    = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            // Пример: фиксированные учетные данные администратора
            $adminUser = 'admin';
            $adminPass = 'admin';  // В реальном приложении храните хеш пароля!

            if ($login === $adminUser && $password === $adminPass) {
                // Успешный вход
                $_SESSION['is_admin'] = true;
                header('Location: /admin');
                exit;
            } else {
                // Неверные данные – подготовим сообщение об ошибке
                $error = 'Неправильный логин или пароль';
            }
        }

        // Показать страницу входа (переменная $error будет установлена при неудаче)
        include __DIR__ . '/../../views/auth/login.php';
    }

    public function logout(): void
    {
        // Удаляем информацию о входе администратора
        unset($_SESSION['is_admin']);
        // Можно также сбросить весь массив сессии или уничтожить сессию:
        // session_destroy();

        // Перенаправляем на главную или страницу входа
        header('Location: /');
        exit;
    }
}
