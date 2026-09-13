<?php
$users = $users ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'User Management') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur-sm">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4" aria-label="Admin navigation">
            <a href="/dashboard" class="text-lg font-bold text-slate-900">Bikinin Admin</a>
            <div class="flex items-center gap-4 text-sm text-slate-600">
                <a href="/dashboard" class="hover:text-slate-900">Dashboard</a>
                <a href="/admin/users" class="font-semibold text-slate-900">Users</a>
                <a href="/logout" class="hover:text-slate-900">Logout</a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Admin</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">User management</h1>
            </div>
            <div class="flex items-center gap-3">
                <form method="get" action="/admin/users" class="flex items-center gap-2">
                    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search users" class="w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none" />
                    <button type="submit" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:border-slate-400">
                        Search
                    </button>
                </form>
                <a href="/admin/users/create" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Add user
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Name</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Created</th>
                        <th class="px-6 py-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-t border-slate-200">
                            <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($user->name) ?></td>
                            <td class="px-6 py-4 text-slate-700"><?= htmlspecialchars($user->email) ?></td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">
                                    <?= htmlspecialchars($user->role) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700"><?= htmlspecialchars($user->createdAt) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="/admin/users/edit?id=<?= (int) $user->id ?>" class="rounded bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700 hover:bg-amber-200">Edit</a>
                                    <a href="/admin/users/delete?id=<?= (int) $user->id ?>" class="rounded bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-200" onclick="return confirm('Delete this user?');">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
