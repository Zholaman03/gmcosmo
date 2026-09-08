<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900" x-data="{ open: false }">

    <!-- Mobile Top Navigation Header -->
    <header class="bg-slate-900 text-white md:hidden flex items-center justify-between p-4 sticky top-0 z-50">
        <span class="text-lg font-bold">Admin Panel</span>
        <button @click="open = !open" class="p-2 rounded hover:bg-slate-800 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </header>

    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar Navigation (Desktop Static / Mobile Slide-down) -->
        <aside :class="open ? 'block' : 'hidden'" class="w-full md:w-64 bg-slate-900 text-white flex-col md:flex md:min-h-screen transition-all">
            <div class="hidden md:block p-6 text-xl font-bold border-b border-slate-800">
                Admin Panel
            </div>
            <nav class="p-4 space-y-2 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium">Главная</a>
                <a href="{{ route('admin.orders') }}" class="block px-4 py-3 rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition">Заказы</a>
                <a href="{{ route('admin.products') }}" class="block px-4 py-3 rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition">Товары</a>
                <a href="{{ route('admin.import') }}" class="block px-4 py-3 rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition">Импорт товаров</a>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-slate-800 rounded-lg">Выйти</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">

            <!-- Profile Summary Card -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm flex flex-row items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Profile</h1>
                    <p class="text-xs sm:text-sm text-gray-500">Manage orders & imports</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="hidden sm:inline font-semibold text-gray-700">{{ auth()->user()->name ?? 'Administrator' }}</span>
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </div>

        

            <!-- Orders Container -->
             @yield('content')
          

        </main>
    </div>

</body>
</html>