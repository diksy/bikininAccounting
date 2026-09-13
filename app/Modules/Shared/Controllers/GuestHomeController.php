<?php

declare(strict_types=1);

namespace App\Modules\Shared\Controllers;

final class GuestHomeController
{
    public function index(): void
    {
        $title = 'Welcome';
        require dirname(__DIR__, 4) . '/resources/views/landing.php';
    }
}
