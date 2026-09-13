<?php

declare(strict_types=1);

namespace App\Support;

final class Router
{
    public static function dispatch(string $uri): void
    {
        $config = require dirname(__DIR__) . '/config.php';
        $routes = $config['routes'];
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (isset($routes[$path])) {
            [$controller, $method] = $routes[$path];
            $instance = new $controller();
            $instance->$method();
            return;
        }

        http_response_code(404);
        echo '<h1>404</h1><p>Page not found.</p>';
    }
}
