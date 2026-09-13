<section class="mx-auto max-w-7xl px-6 py-12">
    <header class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Overview</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Dashboard</h1>
        </div>
        <button type="button" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">
            New transaction
        </button>
    </header>

    <div class="grid gap-6 md:grid-cols-3">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Revenue</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">Rp 128.4M</h2>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Expenses</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">Rp 92.1M</h2>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Profit</p>
            <h2 class="mt-3 text-3xl font-bold text-emerald-600">Rp 36.3M</h2>
        </article>
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-[2fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">Recent transactions</h2>
            <ul class="mt-6 space-y-4">
                <li class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <p class="font-medium text-slate-800">Invoice #2049</p>
                        <p class="text-sm text-slate-500">Client payment</p>
                    </div>
                    <span class="font-semibold text-emerald-600">+ Rp 12.5M</span>
                </li>
                <li class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <p class="font-medium text-slate-800">Office rent</p>
                        <p class="text-sm text-slate-500">Monthly expense</p>
                    </div>
                    <span class="font-semibold text-rose-600">- Rp 4.2M</span>
                </li>
                <li class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-800">Salary payout</p>
                        <p class="text-sm text-slate-500">Payroll</p>
                    </div>
                    <span class="font-semibold text-rose-600">- Rp 8.1M</span>
                </li>
            </ul>
        </section>

        <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">Summary</h2>
            <ul class="mt-6 space-y-4 text-sm text-slate-600">
                <li class="flex justify-between"><span>Cash</span><strong class="text-slate-900">Rp 18.2M</strong></li>
                <li class="flex justify-between"><span>Bank</span><strong class="text-slate-900">Rp 44.6M</strong></li>
                <li class="flex justify-between"><span>Receivables</span><strong class="text-slate-900">Rp 31.8M</strong></li>
                <li class="flex justify-between"><span>Payables</span><strong class="text-slate-900">Rp 17.5M</strong></li>
            </ul>
        </aside>
    </div>
</section>
