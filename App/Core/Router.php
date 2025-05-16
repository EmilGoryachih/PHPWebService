<?php
// core/Router.php

namespace CarCatalog\App\Core;
class Router
{
    protected static array $routes = [];

    public static function add(string $pattern, string $callback): void
    {
        self::$routes[$pattern] = $callback;
    }

    public static function dispatch(string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        if (preg_match('#^/admin#', $uri)) {
            if (empty($_SESSION['is_admin'])) {
                header('Location: /login');
                return;
            }
        }

        foreach (self::$routes as $pattern => $callback) {
            if (preg_match("#^{$pattern}$#", $uri, $matches)) {
                [$controllerClass, $action] = explode('@', $callback);

                $controller = new $controllerClass;

                call_user_func_array([$controller, $action], array_slice($matches, 1));
                return; // не забыли прервать цикл
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }
}
