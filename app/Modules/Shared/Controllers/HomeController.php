<?php

declare(strict_types=1);

namespace App\Modules\Shared\Controllers;

use App\Support\Database;

final class HomeController
{
    public function index(): void
    {
        $pdo = Database::connection();
        $count = $pdo->query('SELECT 1')->fetchColumn();

        $title = 'Welcome to Bikinin Accounting';
        require dirname(__DIR__, 4) . '/resources/views/home.php';
    }
}
