<?php

declare(strict_types=1);

use App\Support\Env;

$env = Env::load(dirname(__DIR__));

return [
    'app' => [
        'name' => Env::get('APP_NAME', 'Bikinin Accounting'),
        'env' => Env::get('APP_ENV', 'development'),
        'debug' => Env::get('APP_DEBUG', 'false') === 'true',
    ],
    'database' => [
        'connection' => Env::get('DB_CONNECTION', 'sqlite'),
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => (int) Env::get('DB_PORT', 3306),
        'database' => Env::get('DB_DATABASE', 'database/app.sqlite'),
        'name' => Env::get('DB_NAME', 'bikinin_accounting'),
        'username' => Env::get('DB_USERNAME', 'root'),
        'password' => Env::get('DB_PASSWORD', ''),
    ],
    'routes' => [
        '/' => ['App\\Modules\\Shared\\Controllers\\HomeController', 'index'],
        '/dashboard' => ['App\\Modules\\Accounting\\Controllers\\DashboardController', 'index'],
    ],
];
