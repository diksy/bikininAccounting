<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Controllers;

use App\Modules\Accounting\Repositories\ChartOfAccountRepository;
use App\Modules\Accounting\Repositories\JournalEntryRepository;
use App\Modules\Shared\Controllers\ProtectedController;
use App\Modules\Users\Models\User;

final class JournalEntryController extends ProtectedController
{
    public function index(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        $repo = new JournalEntryRepository();
        $query = trim((string) ($_GET['q'] ?? ''));
        $entries = $query !== '' ? $repo->search($query) : $repo->all();
        $title = 'Journal Entries';

        require dirname(__DIR__, 4) . '/resources/views/accounting/journal-entries/index.php';
    }

    public function create(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        $accountRepo = new ChartOfAccountRepository();
        $accounts = $accountRepo->all();
        $generatedEntryNo = $this->generateEntryNo();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entryNo = trim((string) ($_POST['entry_no'] ?? $generatedEntryNo));
            $date = trim((string) ($_POST['date'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            $status = trim((string) ($_POST['status'] ?? 'draft'));
            $accountIds = $_POST['line_account_id'] ?? [];
            $notes = $_POST['line_note'] ?? [];
            $debits = $_POST['line_debit'] ?? [];
            $credits = $_POST['line_credit'] ?? [];
            $defaultLineNote = $description !== '' ? $description : 'Journal entry';

            $lines = [];
            foreach ($accountIds as $index => $accountId) {
                $accountId = trim((string) $accountId);
                $lineNote = trim((string) ($notes[$index] ?? ''));
                if ($accountId === '') {
                    continue;
                }

                $lines[] = [
                    'account_id' => $accountId,
                    'note' => $lineNote !== '' ? $lineNote : $defaultLineNote,
                    'debit' => (int) ($debits[$index] ?? 0),
                    'credit' => (int) ($credits[$index] ?? 0),
                ];
            }

            if ($date !== '' && $description !== '' && $lines !== []) {
                $repo = new JournalEntryRepository();
                $repo->create($entryNo, $date, $description, $status, $lines);
                header('Location: /accounting/journal-entries');
                exit;
            }
        }

        $title = 'Create Journal Entry';
        require dirname(__DIR__, 4) . '/resources/views/accounting/journal-entries/create.php';
    }

    public function edit(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        $id = (int) ($_GET['id'] ?? 0);
        $repo = new JournalEntryRepository();
        $entry = $repo->find($id);

        if ($entry === null) {
            header('Location: /accounting/journal-entries');
            exit;
        }

        $accountRepo = new ChartOfAccountRepository();
        $accounts = $accountRepo->all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountIds = $_POST['line_account_id'] ?? [];
            $notes = $_POST['line_note'] ?? [];
            $debits = $_POST['line_debit'] ?? [];
            $credits = $_POST['line_credit'] ?? [];
            $defaultLineNote = trim((string) ($_POST['description'] ?? '')) !== '' ? trim((string) ($_POST['description'] ?? '')) : 'Journal entry';
            $lines = [];

            foreach ($accountIds as $index => $accountId) {
                $accountId = trim((string) $accountId);
                if ($accountId === '') {
                    continue;
                }

                $lines[] = [
                    'account_id' => $accountId,
                    'note' => trim((string) ($notes[$index] ?? '')) !== '' ? trim((string) ($notes[$index] ?? '')) : $defaultLineNote,
                    'debit' => (int) ($debits[$index] ?? 0),
                    'credit' => (int) ($credits[$index] ?? 0),
                ];
            }

            $repo->update($id, [
                'entry_no' => $entry->entryNo,
                'date' => trim((string) ($_POST['date'] ?? '')),
                'description' => trim((string) ($_POST['description'] ?? '')),
                'status' => trim((string) ($_POST['status'] ?? 'draft')),
            ], $lines);

            header('Location: /accounting/journal-entries');
            exit;
        }

        $title = 'Edit Journal Entry';
        require dirname(__DIR__, 4) . '/resources/views/accounting/journal-entries/edit.php';
    }

    public function delete(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $repo = new JournalEntryRepository();
            $repo->delete($id);
        }

        header('Location: /accounting/journal-entries');
        exit;
    }

    private function generateEntryNo(): string
    {
        $repo = new JournalEntryRepository();
        $entries = $repo->all();
        $sequence = count($entries) + 1;

        return 'JE-' . date('Ymd') . '-' . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
