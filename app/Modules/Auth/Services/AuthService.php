<?php

declare(strict_types=1);

namespace App\Modules\Auth\Services;

use App\Modules\Users\Models\User;
use App\Modules\Users\Repositories\UserRepository;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository = new UserRepository()
    ) {
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !isset($user->password) || !password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function register(string $name, string $email, string $password, string $role = User::ROLE_GUEST): ?User
    {
        if (!in_array($role, User::availableRoles(), true)) {
            return null;
        }

        if ($this->userRepository->findByEmail($email) !== null) {
            return null;
        }

        return $this->userRepository->create($name, $email, $password, $role);
    }

    public function requireRole(?User $user, array $allowedRoles): bool
    {
        if ($user === null) {
            return false;
        }

        return $user->hasAnyRole($allowedRoles);
    }
}
