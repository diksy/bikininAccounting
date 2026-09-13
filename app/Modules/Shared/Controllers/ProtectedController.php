<?php

declare(strict_types=1);

namespace App\Modules\Shared\Controllers;

use App\Modules\Auth\Services\AuthService;
use App\Modules\Users\Models\User;

abstract class ProtectedController
{
    public function __construct(
        protected readonly AuthService $authService = new AuthService()
    ) {
    }

    protected function requireAuth(?User $user = null): ?User
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? null;

        if ($userId === null || $role === null) {
            header('Location: /login');
            exit;
        }

        if ($user instanceof User) {
            return $user;
        }

        $repository = new \App\Modules\Users\Repositories\UserRepository();
        $currentUser = $repository->findById((int) $userId);

        if (!$currentUser || !$currentUser->hasRole((string) $role)) {
            header('Location: /login');
            exit;
        }

        return $currentUser;
    }

    protected function requireRole(array $roles): User
    {
        $user = $this->requireAuth();
        if (!$user->hasAnyRole($roles)) {
            http_response_code(403);
            echo '<h1>Forbidden</h1><p>You do not have permission to access this module.</p>';
            exit;
        }

        return $user;
    }
}
