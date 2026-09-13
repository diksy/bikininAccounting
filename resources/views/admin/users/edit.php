<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Edit User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <main class="mx-auto max-w-xl px-6 py-16">
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Admin</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit user</h1>
            </div>

            <form method="post" action="/admin/users/edit?id=<?= (int) $user->id ?>" class="space-y-5">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Full name</label>
                    <input id="name" name="name" type="text" value="<?= htmlspecialchars($user->name) ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="<?= htmlspecialchars($user->email) ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">New password</label>
                    <input id="password" name="password" type="password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" placeholder="Leave blank to keep current password" />
                </div>

                <div>
                    <label for="role" class="mb-2 block text-sm font-medium text-slate-700">Role</label>
                    <select id="role" name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none">
                        <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="accountant" <?= $user->role === 'accountant' ? 'selected' : '' ?>>Accountant</option>
                        <option value="cashier" <?= $user->role === 'cashier' ? 'selected' : '' ?>>Cashier</option>
                        <option value="guest" <?= $user->role === 'guest' ? 'selected' : '' ?>>Guest</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                        Save changes
                    </button>
                    <a href="/admin/users" class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 hover:border-slate-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
