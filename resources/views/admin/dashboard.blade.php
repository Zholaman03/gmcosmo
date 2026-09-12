<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/js/uploadImg.js'])
</head>
<body class="bg-slate-100 font-sans text-slate-900" x-data="{ open: false }">

    <header class="bg-slate-950 text-white md:hidden flex items-center justify-between p-4 sticky top-0 z-50 shadow-lg shadow-slate-900/20">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold">G</span>
            <span class="text-lg font-semibold">GM Cosmo</span>
        </div>
        <button @click="open = !open" class="p-2 rounded-lg hover:bg-slate-800 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </header>

    <div class="flex flex-col md:flex-row min-h-screen">
        <aside :class="open ? 'block' : 'hidden'" class="w-full md:w-72 bg-slate-950 text-slate-200 flex-col md:flex md:min-h-screen border-r border-slate-800/80 transition-all">
            <div class="hidden md:flex items-center gap-3 p-6 border-b border-slate-800/80">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500 text-lg font-bold text-white shadow-lg shadow-indigo-500/30">G</span>
                <div>
                    <div class="text-lg font-semibold text-white">GM Cosmo</div>
                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Admin</div>
                </div>
            </div>

            <nav class="p-4 space-y-2 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20">Главная</a>
                <a href="{{ route('admin.orders') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Заказы</a>
                <a href="{{ route('admin.products') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Товары</a>
                <a href="{{ route('admin.import') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Импорт товаров</a>
            </nav>

            <div class="p-4 border-t border-slate-800/80">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-4 py-3 text-left text-sm font-medium text-red-300 transition hover:border-red-500 hover:bg-red-500/10 hover:text-red-200">Выйти</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>