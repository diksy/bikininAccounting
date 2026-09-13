<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

final class JournalEntryLine
{
    public function __construct(
        public int $id,
        public int $journalEntryId,
        public string $accountId,
        public string $note,
        public int $debit,
        public int $credit,
        public string $createdAt,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) ($row['id'] ?? 0),
            (int) ($row['journal_entry_id'] ?? 0),
            (string) ($row['account_id'] ?? ''),
            (string) ($row['note'] ?? ''),
            (int) ($row['debit'] ?? 0),
            (int) ($row['credit'] ?? 0),
            (string) ($row['created_at'] ?? ''),
        );
    }
}
