<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Bikinin Accounting') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="description" content="Modular monolith accounting platform.">
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur-sm">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4" aria-label="Main navigation">
            <a href="/" class="text-xl font-bold tracking-tight text-slate-900">Bikinin</a>
            <div class="flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="/" class="hover:text-slate-900">Home</a>
                <a href="/dashboard" class="hover:text-slate-900">Dashboard</a>
                <a href="#" class="hover:text-slate-900">Reports</a>
            </div>
        </nav>
    </header>

    <main>
        <?php require $contentView; ?>
    </main>
</body>
</html>
