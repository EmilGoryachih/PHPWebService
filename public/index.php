<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

require_once __DIR__ . '/../App/Core/routes.php';

\CarCatalog\App\Core\Router::dispatch($_SERVER['REQUEST_URI']);
