<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

final class JournalEntry
{
    public function __construct(
        public int $id,
        public string $entryNo,
        public string $date,
        public string $description,
        public int $debit,
        public int $credit,
        public string $status,
        public string $createdAt,
        public array $lines = [],
    ) {
    }

    public static function fromRow(array $row, array $lines = []): self
    {
        return new self(
            (int) ($row['id'] ?? 0),
            (string) ($row['entry_no'] ?? ''),
            (string) ($row['date'] ?? ''),
            (string) ($row['description'] ?? ''),
            (int) ($row['debit'] ?? 0),
            (int) ($row['credit'] ?? 0),
            (string) ($row['status'] ?? 'draft'),
            (string) ($row['created_at'] ?? ''),
            $lines,
        );
    }

    public function isBalanced(): bool
    {
        return $this->debit === $this->credit;
    }

    public static function validStatuses(): array
    {
        return ['draft', 'posted'];
    }
}
