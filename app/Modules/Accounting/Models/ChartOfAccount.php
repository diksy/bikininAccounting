<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

final class ChartOfAccount
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $type,
        public string $normalBalance,
        public bool $isPostable,
        public string $createdAt,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (string) ($row['id'] ?? ''),
            (string) ($row['name'] ?? ''),
            isset($row['type']) && $row['type'] !== '' ? (string) $row['type'] : null,
            (string) ($row['normal_balance'] ?? 'debit'),
            (bool) ($row['is_postable'] ?? true),
            (string) ($row['created_at'] ?? ''),
        );
    }

    public static function schema(): array
    {
        $path = dirname(__DIR__) . '/concept.json';
        $content = @file_get_contents($path);

        if ($content === false) {
            return [];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function isValidNormalBalance(): bool
    {
        return in_array(strtolower($this->normalBalance), ['debit', 'credit'], true);
    }
}
