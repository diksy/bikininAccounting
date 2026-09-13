<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

final class User
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_ACCOUNTANT = 'accountant';
    public const ROLE_CASHIER = 'cashier';
    public const ROLE_GUEST = 'guest';

    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $role,
        public string $createdAt,
        public ?string $password = null,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) ($row['id'] ?? 0),
            (string) ($row['name'] ?? ''),
            (string) ($row['email'] ?? ''),
            (string) ($row['role'] ?? self::ROLE_GUEST),
            (string) ($row['created_at'] ?? ''),
            $row['password'] ?? null,
        );
    }

    public static function availableRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_ACCOUNTANT,
            self::ROLE_CASHIER,
            self::ROLE_GUEST,
        ];
    }

    public function hasRole(string $role): bool
    {
        return strtolower($this->role) === strtolower($role);
    }

    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function canAccessModule(string $module): bool
    {
        return match ($this->role) {
            self::ROLE_ADMIN => true,
            self::ROLE_ACCOUNTANT => in_array($module, ['dashboard', 'accounting', 'reports'], true),
            self::ROLE_CASHIER => in_array($module, ['dashboard', 'cashier', 'transactions'], true),
            self::ROLE_GUEST => in_array($module, ['dashboard'], true),
            default => false,
        };
    }
}
