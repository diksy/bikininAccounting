<?php

declare(strict_types=1);

namespace App\Modules\Users\Repositories;

use App\Modules\Users\Models\User;
use App\Support\Database;
use PDO;

final class UserRepository
{
    public function __construct()
    {
        $this->ensureSchema();
    }

    public function findByEmail(string $email): ?User
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => strtolower(trim($email))]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? User::fromRow($row) : null;
    }

    public function findById(int $id): ?User
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? User::fromRow($row) : null;
    }

    public function create(string $name, string $email, string $password, string $role): User
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $statement = $pdo->prepare(
            'INSERT INTO users (name, email, password, role, created_at) VALUES (:name, :email, :password, :role, :created_at)'
        );

        $statement->execute([
            'name' => $name,
            'email' => strtolower(trim($email)),
            'password' => $passwordHash,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $id = (int) $pdo->lastInsertId();

        return new User($id, $name, strtolower(trim($email)), $role, date('Y-m-d H:i:s'), $passwordHash);
    }

    public function all(): array
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->query('SELECT * FROM users ORDER BY created_at DESC');
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): User => User::fromRow($row), $rows);
    }

    public function search(string $query): array
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $search = '%' . trim($query) . '%';
        $statement = $pdo->prepare(
            'SELECT * FROM users WHERE name LIKE :search OR email LIKE :search OR role LIKE :search ORDER BY created_at DESC'
        );
        $statement->execute(['search' => $search]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): User => User::fromRow($row), $rows);
    }

    public function update(int $id, array $data): bool
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = trim((string) $data['name']);
        }

        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = strtolower(trim((string) $data['email']));
        }

        if (isset($data['role']) && in_array($data['role'], User::availableRoles(), true)) {
            $fields[] = 'role = :role';
            $params['role'] = $data['role'];
        }

        if (isset($data['password']) && trim((string) $data['password']) !== '') {
            $fields[] = 'password = :password';
            $params['password'] = password_hash((string) $data['password'], PASSWORD_DEFAULT);
        }

        if ($fields === []) {
            return false;
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $statement = $pdo->prepare($sql);

        return $statement->execute($params);
    }

    public function delete(int $id): bool
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->prepare('DELETE FROM users WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    public function ensureSchema(): void
    {
        $pdo = Database::connection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT "guest",
                created_at TEXT NOT NULL
            )'
        );
    }
}
