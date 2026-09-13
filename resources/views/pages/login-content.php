<section class="mx-auto max-w-md px-6 py-16">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-slate-900">Sign in</h1>
        <p class="mt-2 text-sm text-slate-600">Access your accounting workspace with your role.</p>

        <form method="post" action="/login" class="mt-8 space-y-5">
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
            </div>

            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            No account yet?
            <a href="/register" class="font-semibold text-slate-900">Create one</a>
        </p>
    </div>
</section>
