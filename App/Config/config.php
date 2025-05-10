<?php
namespace CarCatalog\App\Config;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
// dirname(__DIR__, 2) = корень проекта (/var/www/html)
$dotenv->load();

return [
    'host'     => $_ENV['DB_HOST'],
    'dbname'   => $_ENV['DB_NAME'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
];
