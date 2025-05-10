<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1) Composer-автозагрузка + .env
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

// 2) Подключение маршрутов
require_once __DIR__ . '/../App/Core/routes.php';

// 3) Запуск диспетчера
\CarCatalog\App\Core\Router::dispatch($_SERVER['REQUEST_URI']);
