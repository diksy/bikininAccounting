<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\Models\ChartOfAccount;
use App\Support\Database;
use PDO;

final class ChartOfAccountRepository
{
    public function __construct()
    {
        $this->ensureSchema();
        $this->seedCommonAccounts();
    }

    public function all(): array
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->query('SELECT * FROM chart_of_accounts ORDER BY id ASC');
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): ChartOfAccount => ChartOfAccount::fromRow($row), $rows);
    }

    public function find(string $id): ?ChartOfAccount
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM chart_of_accounts WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? ChartOfAccount::fromRow($row) : null;
    }

    public function search(string $query): array
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $search = '%' . trim($query) . '%';
        $statement = $pdo->prepare(
            'SELECT * FROM chart_of_accounts WHERE id LIKE :search OR name LIKE :search OR type LIKE :search OR normal_balance LIKE :search ORDER BY id ASC'
        );
        $statement->execute(['search' => $search]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): ChartOfAccount => ChartOfAccount::fromRow($row), $rows);
    }

    public function create(string $id, string $name, ?string $type, string $normalBalance, bool $isPostable): ChartOfAccount
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->prepare(
            'INSERT INTO chart_of_accounts (id, name, type, normal_balance, is_postable, created_at) VALUES (:id, :name, :type, :normal_balance, :is_postable, :created_at)'
        );

        $statement->execute([
            'id' => strtoupper(trim($id)),
            'name' => trim($name),
            'type' => $type !== null && trim($type) !== '' ? trim($type) : null,
            'normal_balance' => strtolower($normalBalance),
            'is_postable' => $isPostable ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return new ChartOfAccount(
            strtoupper(trim($id)),
            trim($name),
            $type !== null && trim($type) !== '' ? trim($type) : null,
            strtolower($normalBalance),
            $isPostable,
            date('Y-m-d H:i:s')
        );
    }

    public function update(string $id, array $data): bool
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $fields = [];
        $params = ['id' => strtoupper(trim($id))];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = trim((string) $data['name']);
        }

        if (array_key_exists('type', $data)) {
            $fields[] = 'type = :type';
            $params['type'] = $data['type'] !== null && trim((string) $data['type']) !== '' ? trim((string) $data['type']) : null;
        }

        if (isset($data['normal_balance'])) {
            $fields[] = 'normal_balance = :normal_balance';
            $params['normal_balance'] = strtolower((string) $data['normal_balance']);
        }

        if (isset($data['is_postable'])) {
            $fields[] = 'is_postable = :is_postable';
            $params['is_postable'] = (bool) $data['is_postable'] ? 1 : 0;
        }

        if ($fields === []) {
            return false;
        }

        $statement = $pdo->prepare('UPDATE chart_of_accounts SET ' . implode(', ', $fields) . ' WHERE id = :id');

        return $statement->execute($params);
    }

    public function delete(string $id): bool
    {
        $this->ensureSchema();
        $pdo = Database::connection();
        $statement = $pdo->prepare('DELETE FROM chart_of_accounts WHERE id = :id');

        return $statement->execute(['id' => strtoupper(trim($id))]);
    }

    public function ensureSchema(): void
    {
        $pdo = Database::connection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS chart_of_accounts (
                id TEXT PRIMARY KEY,
                name TEXT NOT NULL,
                type TEXT NULL,
                normal_balance TEXT NOT NULL DEFAULT "debit",
                is_postable INTEGER NOT NULL DEFAULT 1,
                created_at TEXT NOT NULL
            )'
        );
    }

    public function seedCommonAccounts(): void
    {
        $pdo = Database::connection();
        $seed = [
            ['1010', 'Cash in Bank', 'Asset', 'debit', 1],
            ['1020', 'Cash on Hand', 'Asset', 'debit', 1],
            ['1100', 'Accounts Receivable', 'Asset', 'debit', 1],
            ['1200', 'Inventory', 'Asset', 'debit', 1],
            ['1500', 'Office Equipment', 'Asset', 'debit', 1],
            ['2000', 'Accounts Payable', 'Liability', 'credit', 1],
            ['2100', 'Taxes Payable', 'Liability', 'credit', 1],
            ['3000', 'Owner Equity', 'Equity', 'credit', 1],
            ['3100', 'Retained Earnings', 'Equity', 'credit', 1],
            ['4000', 'Sales Revenue', 'Revenue', 'credit', 1],
            ['5000', 'Cost of Goods Sold', 'Expense', 'debit', 1],
            ['5100', 'Operating Expense', 'Expense', 'debit', 1],
        ];

        foreach ($seed as [$id, $name, $type, $normalBalance, $isPostable]) {
            $statement = $pdo->prepare(
                'INSERT OR IGNORE INTO chart_of_accounts (id, name, type, normal_balance, is_postable, created_at) VALUES (:id, :name, :type, :normal_balance, :is_postable, :created_at)'
            );

            $statement->execute([
                'id' => strtoupper((string) $id),
                'name' => $name,
                'type' => $type,
                'normal_balance' => strtolower((string) $normalBalance),
                'is_postable' => (bool) $isPostable ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
