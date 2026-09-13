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
        '/' => ['App\\Modules\\Shared\\Controllers\\GuestHomeController', 'index'],
        '/login' => ['App\\Modules\\Auth\\Controllers\\AuthController', 'login'],
        '/register' => ['App\\Modules\\Auth\\Controllers\\AuthController', 'register'],
        '/logout' => ['App\\Modules\\Auth\\Controllers\\AuthController', 'logout'],
        '/dashboard' => ['App\\Modules\\Accounting\\Controllers\\DashboardController', 'index'],
        '/admin/users' => ['App\\Modules\\Admin\\Controllers\\UserManagementController', 'index'],
        '/admin/users/create' => ['App\\Modules\\Admin\\Controllers\\UserManagementController', 'create'],
        '/admin/users/edit' => ['App\\Modules\\Admin\\Controllers\\UserManagementController', 'edit'],
        '/admin/users/delete' => ['App\\Modules\\Admin\\Controllers\\UserManagementController', 'delete'],
        '/accounting/chart-of-accounts' => ['App\\Modules\\Accounting\\Controllers\\ChartOfAccountController', 'index'],
        '/accounting/chart-of-accounts/create' => ['App\\Modules\\Accounting\\Controllers\\ChartOfAccountController', 'create'],
        '/accounting/chart-of-accounts/edit' => ['App\\Modules\\Accounting\\Controllers\\ChartOfAccountController', 'edit'],
        '/accounting/chart-of-accounts/delete' => ['App\\Modules\\Accounting\\Controllers\\ChartOfAccountController', 'delete'],
        '/accounting/journal-entries' => ['App\\Modules\\Accounting\\Controllers\\JournalEntryController', 'index'],
        '/accounting/journal-entries/create' => ['App\\Modules\\Accounting\\Controllers\\JournalEntryController', 'create'],
        '/accounting/journal-entries/edit' => ['App\\Modules\\Accounting\\Controllers\\JournalEntryController', 'edit'],
        '/accounting/journal-entries/delete' => ['App\\Modules\\Accounting\\Controllers\\JournalEntryController', 'delete'],
    ],
];
