<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\Models\JournalEntry;
use App\Modules\Accounting\Models\JournalEntryLine;
use App\Support\Database;
use PDO;

final class JournalEntryRepository
{
    public function __construct()
    {
        $this->ensureSchema();
    }

    public function all(): array
    {
        $pdo = Database::connection();
        $statement = $pdo->query('SELECT * FROM journal_entries ORDER BY date DESC, id DESC');
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $entries = [];
        foreach ($rows as $row) {
            $entries[] = JournalEntry::fromRow($row, $this->findLinesByEntryId((int) $row['id']));
        }

        return $entries;
    }

    public function find(int $id): ?JournalEntry
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM journal_entries WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return JournalEntry::fromRow($row, $this->findLinesByEntryId($id));
    }

    public function search(string $query): array
    {
        $pdo = Database::connection();
        $search = '%' . trim($query) . '%';
        $statement = $pdo->prepare(
            'SELECT * FROM journal_entries WHERE entry_no LIKE :search OR description LIKE :search OR status LIKE :search ORDER BY date DESC, id DESC'
        );
        $statement->execute(['search' => $search]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $entries = [];
        foreach ($rows as $row) {
            $entries[] = JournalEntry::fromRow($row, $this->findLinesByEntryId((int) $row['id']));
        }

        return $entries;
    }

    public function create(string $entryNo, string $date, string $description, string $status, array $lines): JournalEntry
    {
        $pdo = Database::connection();
        $totals = $this->sumLineAmounts($lines);

        $statement = $pdo->prepare(
            'INSERT INTO journal_entries (entry_no, date, description, debit, credit, status, created_at) VALUES (:entry_no, :date, :description, :debit, :credit, :status, :created_at)'
        );

        $statement->execute([
            'entry_no' => trim($entryNo),
            'date' => $date,
            'description' => trim($description),
            'debit' => $totals['debit'],
            'credit' => $totals['credit'],
            'status' => in_array($status, JournalEntry::validStatuses(), true) ? $status : 'draft',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $entryId = (int) $pdo->lastInsertId();

        foreach ($lines as $line) {
            $this->createLine($entryId, (string) $line['account_id'], (string) $line['note'], (int)($line['debit'] ?? 0), (int)($line['credit'] ?? 0));
        }

        return $this->find($entryId) ?? new JournalEntry(
            $entryId,
            trim($entryNo),
            $date,
            trim($description),
            $totals['debit'],
            $totals['credit'],
            in_array($status, JournalEntry::validStatuses(), true) ? $status : 'draft',
            date('Y-m-d H:i:s'),
            []
        );
    }

    public function update(int $id, array $data, ?array $lines = null): bool
    {
        $pdo = Database::connection();
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['entry_no'])) {
            $fields[] = 'entry_no = :entry_no';
            $params['entry_no'] = trim((string) $data['entry_no']);
        }

        if (isset($data['date'])) {
            $fields[] = 'date = :date';
            $params['date'] = trim((string) $data['date']);
        }

        if (isset($data['description'])) {
            $fields[] = 'description = :description';
            $params['description'] = trim((string) $data['description']);
        }

        if (isset($data['status']) && in_array($data['status'], JournalEntry::validStatuses(), true)) {
            $fields[] = 'status = :status';
            $params['status'] = $data['status'];
        }

        if ($fields !== []) {
            $sql = 'UPDATE journal_entries SET ' . implode(', ', $fields) . ' WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
        }

        if ($lines !== null) {
            $pdo->prepare('DELETE FROM journal_entry_lines WHERE journal_entry_id = :entry_id')->execute(['entry_id' => $id]);
            $totals = $this->sumLineAmounts($lines);
            foreach ($lines as $line) {
                $this->createLine($id, (string) $line['account_id'], (string) $line['note'], (int)($line['debit'] ?? 0), (int)($line['credit'] ?? 0));
            }
            $pdo->prepare('UPDATE journal_entries SET debit = :debit, credit = :credit WHERE id = :id')->execute([
                'debit' => $totals['debit'],
                'credit' => $totals['credit'],
                'id' => $id,
            ]);
        }

        return true;
    }

    public function delete(int $id): bool
    {
        $pdo = Database::connection();
        $pdo->prepare('DELETE FROM journal_entry_lines WHERE journal_entry_id = :entry_id')->execute(['entry_id' => $id]);
        $statement = $pdo->prepare('DELETE FROM journal_entries WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    public function ensureSchema(): void
    {
        $pdo = Database::connection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS journal_entries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                entry_no TEXT NOT NULL UNIQUE,
                date TEXT NOT NULL,
                description TEXT NOT NULL,
                debit INTEGER NOT NULL DEFAULT 0,
                credit INTEGER NOT NULL DEFAULT 0,
                status TEXT NOT NULL DEFAULT "draft",
                created_at TEXT NOT NULL
            )'
        );

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS journal_entry_lines (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                journal_entry_id INTEGER NOT NULL,
                account_id TEXT NOT NULL,
                note TEXT NOT NULL,
                debit INTEGER NOT NULL DEFAULT 0,
                credit INTEGER NOT NULL DEFAULT 0,
                created_at TEXT NOT NULL,
                FOREIGN KEY(journal_entry_id) REFERENCES journal_entries(id) ON DELETE CASCADE
            )'
        );
    }

    private function findLinesByEntryId(int $entryId): array
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM journal_entry_lines WHERE journal_entry_id = :journal_entry_id ORDER BY id ASC');
        $statement->execute(['journal_entry_id' => $entryId]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): JournalEntryLine => JournalEntryLine::fromRow($row), $rows);
    }

    private function createLine(int $entryId, string $accountId, string $note, int $debit, int $credit): void
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare(
            'INSERT INTO journal_entry_lines (journal_entry_id, account_id, note, debit, credit, created_at) VALUES (:journal_entry_id, :account_id, :note, :debit, :credit, :created_at)'
        );

        $statement->execute([
            'journal_entry_id' => $entryId,
            'account_id' => trim($accountId),
            'note' => trim($note),
            'debit' => max(0, $debit),
            'credit' => max(0, $credit),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function sumLineAmounts(array $lines): array
    {
        $debit = 0;
        $credit = 0;

        foreach ($lines as $line) {
            $debit += (int) ($line['debit'] ?? 0);
            $credit += (int) ($line['credit'] ?? 0);
        }

        return ['debit' => $debit, 'credit' => $credit];
    }
}
