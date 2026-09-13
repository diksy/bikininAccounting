<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Edit Journal Entry') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <main class="mx-auto max-w-4xl px-6 py-16">
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Accounting</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit journal entry</h1>
            </div>

            <form method="post" action="/accounting/journal-entries/edit?id=<?= urlencode((string)$entry->id) ?>" class="space-y-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="entry_no" class="mb-2 block text-sm font-medium text-slate-700">Entry No</label>
                        <input id="entry_no" name="entry_no" type="text" value="<?= htmlspecialchars($entry->entryNo) ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                    </div>
                    <div>
                        <label for="date" class="mb-2 block text-sm font-medium text-slate-700">Date</label>
                        <input id="date" name="date" type="date" value="<?= htmlspecialchars($entry->date) ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Description</label>
                    <input id="description" name="description" type="text" value="<?= htmlspecialchars($entry->description) ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none">
                        <option value="draft" <?= $entry->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="posted" <?= $entry->status === 'posted' ? 'selected' : '' ?>>Posted</option>
                    </select>
                </div>

                <div class="space-y-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="grid gap-4 md:grid-cols-5">
                        <div class="md:col-span-2"><label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Account</label></div>
                        <div><label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Note</label></div>
                        <div><label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Debit</label></div>
                        <div><label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Credit</label></div>
                    </div>

                    <div id="journal-lines" class="space-y-4">
                        <?php foreach ($entry->lines as $line): ?>
                            <div class="journal-line grid gap-4 md:grid-cols-5">
                                <select name="line_account_id[]" class="md:col-span-2 rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none">
                                    <option value="">Select account</option>
                                    <?php foreach ($accounts as $account): ?>
                                        <option value="<?= htmlspecialchars($account->id) ?>" <?= $account->id === $line->accountId ? 'selected' : '' ?>><?= htmlspecialchars($account->id) ?> - <?= htmlspecialchars($account->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="line_note[]" value="<?= htmlspecialchars($line->note) ?>" class="rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                                <input type="number" min="0" name="line_debit[]" value="<?= htmlspecialchars((string)$line->debit) ?>" class="rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                                <input type="number" min="0" name="line_credit[]" value="<?= htmlspecialchars((string)$line->credit) ?>" class="rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" id="add-journal-line" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:border-slate-400">Add row</button>
                        <button type="button" id="remove-journal-line" class="rounded-lg border border-rose-300 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 hover:border-rose-400">Delete row</button>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700">Update entry</button>
                    <a href="/accounting/journal-entries" class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 hover:border-slate-400">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const lineContainer = document.getElementById('journal-lines');
        const addButton = document.getElementById('add-journal-line');
        const removeButton = document.getElementById('remove-journal-line');

        addButton.addEventListener('click', () => {
            const firstRow = lineContainer.querySelector('.journal-line');
            if (!firstRow) {
                return;
            }

            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input, select').forEach((field) => {
                if (field.tagName === 'INPUT') {
                    field.value = field.name.includes('debit') ? '0' : field.name.includes('credit') ? '0' : '';
                    return;
                }

                field.selectedIndex = 0;
            });

            lineContainer.appendChild(newRow);
        });

        removeButton.addEventListener('click', () => {
            const rows = lineContainer.querySelectorAll('.journal-line');
            if (rows.length > 1) {
                lineContainer.removeChild(rows[rows.length - 1]);
            }
        });
    </script>
</body>
</html>
