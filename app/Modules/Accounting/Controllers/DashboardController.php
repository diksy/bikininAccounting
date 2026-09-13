<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Controllers;

use App\Modules\Shared\Controllers\ProtectedController;
use App\Modules\Users\Models\User;

final class DashboardController extends ProtectedController
{
    public function index(): void
    {
        $this->requireRole([
            User::ROLE_ADMIN,
            User::ROLE_ACCOUNTANT,
            User::ROLE_CASHIER,
            User::ROLE_GUEST,
        ]);

        $title = 'Dashboard';
        require dirname(__DIR__, 4) . '/resources/views/dashboard.php';
    }
}
