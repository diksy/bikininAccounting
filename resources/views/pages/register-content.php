<section class="mx-auto max-w-lg px-6 py-16">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-slate-900">Create account</h1>
        <p class="mt-2 text-sm text-slate-600">Your account will be created as a guest by default.</p>

        <form method="post" action="/register" class="mt-8 space-y-5">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Full name</label>
                <input id="name" name="name" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-slate-500 focus:outline-none" />
            </div>

            <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-500">
                Register</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            Already have an account?
            <a href="/login" class="font-semibold text-slate-900">Login</a>
        </p>
    </div>
</section>
