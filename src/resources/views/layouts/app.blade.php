<!DOCTYPE html>
<html lang="id" x-data="{ dark: localStorage.getItem('astra-dark') === 'true' }" x-init="$watch('dark', v => localStorage.setItem('astra-dark', v))" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'Astra') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed z-40 inset-y-0 left-0 w-64 transform lg:translate-x-0 transition-transform duration-200 lg:static lg:flex flex-col bg-white/80 dark:bg-gray-900/90 backdrop-blur border-r border-white/60 dark:border-gray-800">
            <div class="px-6 py-6 flex items-center gap-2">
                <span class="text-astra-600 dark:text-astra-300 text-xl"></span>
                <span class="font-serif text-xl font-semibold tracking-wide">Astra</span>
            </div>
            <nav class="flex-1 px-4 space-y-1 text-sm">
                @php
                    $navItems = [
    ['route' => 'dashboard', 'label' => 'Dashboard'],
    ['route' => 'tasks.index', 'label' => 'Tugas'],
    ['route' => 'categories.index', 'label' => 'Kategori'],
    ['route' => 'tags.index', 'label' => 'Tag'],
    ['route' => 'history.index', 'label' => 'Riwayat'],
    ['route' => 'profile.edit', 'label' => 'Profil'],
];
                @endphp
                @php
                    $navPatterns = [
                        'dashboard' => 'dashboard*',
                        'tasks.index' => 'tasks.*',
                        'categories.index' => 'categories.*',
                        'tags.index' => 'tags.*',
                        'history.index' => 'history.*',
                        'profile.edit' => 'profile.*',
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition
                              {{ request()->routeIs($navPatterns[$item['route']])
                                    ? 'bg-astra-button text-white shadow-soft'
                                    : 'text-gray-600 dark:text-gray-300 hover:bg-astra-50 dark:hover:bg-gray-800' }}">
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="p-4 space-y-2">
                <button @click="dark = !dark" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-astra-50 dark:hover:bg-gray-800 text-sm">
                    <span></span>
                    <span x-text="dark ? 'Mode Terang' : 'Mode Gelap'"></span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-950/40 text-sm">
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
        <div class="fixed inset-0 bg-black/30 z-30 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak></div>

        <!-- Main -->
        <div class="flex-1 flex flex-col min-w-0">
            <header class="sticky top-0 z-20 bg-white/70 dark:bg-gray-900/70 backdrop-blur border-b border-white/60 dark:border-gray-800 px-4 sm:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button class="lg:hidden text-gray-600 dark:text-gray-300" @click="sidebarOpen = true"></button>
                    <h1 class="text-lg sm:text-xl font-serif font-semibold">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Halo, <span class="font-medium text-astra-700 dark:text-astra-300">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-8">
                @if (session('success'))
                    <div class="mb-6 card px-5 py-3 text-sm text-astra-700 dark:text-astra-200 border-l-4 border-astra-500">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 card px-5 py-3 text-sm text-red-600 border-l-4 border-red-400">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
