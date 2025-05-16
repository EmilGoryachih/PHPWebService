<?php
namespace CarCatalog\App\Controllers;

class AuthController
{
    public function login(): void
    {
        if (!empty($_SESSION['is_admin'])) {
            header('Location: /admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login    = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            $adminUser = 'admin';
            $adminPass = 'admin';

            if ($login === $adminUser && $password === $adminPass) {
                $_SESSION['is_admin'] = true;
                header('Location: /admin');
                exit;
            } else {
                $error = 'Неправильный логин или пароль';
            }
        }

        include __DIR__ . '/../../views/auth/login.php';
    }

    public function logout(): void
    {
        unset($_SESSION['is_admin']);

        header('Location: /');
        exit;
    }
}
