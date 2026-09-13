<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Modules\Shared\Controllers\ProtectedController;
use App\Modules\Users\Models\User;
use App\Modules\Users\Repositories\UserRepository;

final class UserManagementController extends ProtectedController
{
    public function index(): void
    {
        $this->requireRole([User::ROLE_ADMIN]);

        $repo = new UserRepository();
        $search = trim((string) ($_GET['q'] ?? ''));
        $users = $search !== '' ? $repo->search($search) : $repo->all();
        $title = 'User Management';

        require dirname(__DIR__, 4) . '/resources/views/admin/users/index.php';
    }

    public function create(): void
    {
        $this->requireRole([User::ROLE_ADMIN]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $role = (string) ($_POST['role'] ?? User::ROLE_GUEST);

            if ($name !== '' && $email !== '' && $password !== '' && in_array($role, User::availableRoles(), true)) {
                $repo = new UserRepository();
                $repo->create($name, $email, $password, $role);
                header('Location: /admin/users');
                exit;
            }
        }

        $title = 'Create User';
        require dirname(__DIR__, 4) . '/resources/views/admin/users/create.php';
    }

    public function edit(): void
    {
        $this->requireRole([User::ROLE_ADMIN]);

        $id = (int) ($_GET['id'] ?? 0);
        $repo = new UserRepository();
        $user = $repo->findById($id);

        if ($user === null) {
            header('Location: /admin/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = [
                'name' => trim((string) ($_POST['name'] ?? '')),
                'email' => trim((string) ($_POST['email'] ?? '')),
                'role' => trim((string) ($_POST['role'] ?? '')),
            ];

            if (isset($_POST['password']) && trim((string) $_POST['password']) !== '') {
                $payload['password'] = (string) $_POST['password'];
            }

            $repo->update($id, $payload);
            header('Location: /admin/users');
            exit;
        }

        $title = 'Edit User';
        require dirname(__DIR__, 4) . '/resources/views/admin/users/edit.php';
    }

    public function delete(): void
    {
        $this->requireRole([User::ROLE_ADMIN]);

        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $repo = new UserRepository();
            $repo->delete($id);
        }

        header('Location: /admin/users');
        exit;
    }
}
