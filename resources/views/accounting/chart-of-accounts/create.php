<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Create Account') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <main class="mx-auto max-w-xl px-6 py-16">
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Accounting</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Create account</h1>
            </div>

            <form method="post" action="/accounting/chart-of-accounts/create" class="space-y-5">
                <div>
                    <label for="id" class="mb-2 block text-sm font-medium text-slate-700">Account code</label>
                    <input id="id" name="id" type="text" required maxlength="10" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Account name</label>
                    <input id="name" name="name" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                </div>

                <div>
                    <label for="type" class="mb-2 block text-sm font-medium text-slate-700">Type</label>
                    <input id="type" name="type" type="text" placeholder="Asset, Liability, Equity, Revenue, Expense" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                </div>

                <div>
                    <label for="normal_balance" class="mb-2 block text-sm font-medium text-slate-700">Normal balance</label>
                    <select id="normal_balance" name="normal_balance" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none">
                        <option value="debit" selected>Debit</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <input id="is_postable" name="is_postable" type="checkbox" value="1" checked class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500" />
                    <label for="is_postable" class="text-sm font-medium text-slate-700">Postable</label>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                        Save account
                    </button>
                    <a href="/accounting/chart-of-accounts" class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 hover:border-slate-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
