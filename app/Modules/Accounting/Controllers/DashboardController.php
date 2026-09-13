<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Controllers;

final class DashboardController
{
    public function index(): void
    {
        $title = 'Dashboard';
        require dirname(__DIR__, 4) . '/resources/views/dashboard.php';
    }
}
