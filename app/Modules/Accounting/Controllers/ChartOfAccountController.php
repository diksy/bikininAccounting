<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Controllers;

use App\Modules\Accounting\Repositories\ChartOfAccountRepository;
use App\Modules\Shared\Controllers\ProtectedController;
use App\Modules\Users\Models\User;

final class ChartOfAccountController extends ProtectedController
{
    public function index(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        $repo = new ChartOfAccountRepository();
        $query = trim((string) ($_GET['q'] ?? ''));
        $accounts = $query !== '' ? $repo->search($query) : $repo->all();
        $title = 'Chart of Accounts';

        require dirname(__DIR__, 4) . '/resources/views/accounting/chart-of-accounts/index.php';
    }

    public function create(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim((string) ($_POST['id'] ?? ''));
            $name = trim((string) ($_POST['name'] ?? ''));
            $type = trim((string) ($_POST['type'] ?? ''));
            $normalBalance = trim((string) ($_POST['normal_balance'] ?? 'debit'));
            $isPostable = isset($_POST['is_postable']) && $_POST['is_postable'] === '1';

            if ($id !== '' && $name !== '' && in_array(strtolower($normalBalance), ['debit', 'credit'], true)) {
                $repo = new ChartOfAccountRepository();
                $repo->create($id, $name, $type !== '' ? $type : null, $normalBalance, $isPostable);
                header('Location: /accounting/chart-of-accounts');
                exit;
            }
        }

        $title = 'Create Chart of Account';
        require dirname(__DIR__, 4) . '/resources/views/accounting/chart-of-accounts/create.php';
    }

    public function edit(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        $id = trim((string) ($_GET['id'] ?? ''));
        $repo = new ChartOfAccountRepository();
        $account = $repo->find($id);

        if ($account === null) {
            header('Location: /accounting/chart-of-accounts');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = [
                'name' => trim((string) ($_POST['name'] ?? '')),
                'type' => trim((string) ($_POST['type'] ?? '')),
                'normal_balance' => trim((string) ($_POST['normal_balance'] ?? 'debit')),
                'is_postable' => isset($_POST['is_postable']) && $_POST['is_postable'] === '1',
            ];

            $repo->update($id, $payload);
            header('Location: /accounting/chart-of-accounts');
            exit;
        }

        $title = 'Edit Chart of Account';
        require dirname(__DIR__, 4) . '/resources/views/accounting/chart-of-accounts/edit.php';
    }

    public function delete(): void
    {
        $this->requireRole([User::ROLE_ADMIN, User::ROLE_ACCOUNTANT]);

        $id = trim((string) ($_GET['id'] ?? ''));
        if ($id !== '') {
            $repo = new ChartOfAccountRepository();
            $repo->delete($id);
        }

        header('Location: /accounting/chart-of-accounts');
        exit;
    }
}
