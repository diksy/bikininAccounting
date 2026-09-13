<?php

declare(strict_types=1);

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Services\AuthService;
use App\Modules\Users\Models\User;

final class AuthController
{
    public function __construct(
        private readonly AuthService $authService = new AuthService()
    ) {
    }

    public function register(): void
    {
        $this->prepareSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $role = (string) ($_POST['role'] ?? User::ROLE_GUEST);

            $user = $this->authService->register($name, $email, $password, $role);

            if ($user) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_role'] = $user->role;
                header('Location: /dashboard');
                exit;
            }
        }

        $title = 'Register';
        require dirname(__DIR__, 4) . '/resources/views/auth/register.php';
    }

    public function login(): void
    {
        $this->prepareSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            $user = $this->authService->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_role'] = $user->role;
                header('Location: /dashboard');
                exit;
            }
        }

        $title = 'Login';
        require dirname(__DIR__, 4) . '/resources/views/auth/login.php';
    }

    public function logout(): void
    {
        $this->prepareSession();
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }

    private function prepareSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
