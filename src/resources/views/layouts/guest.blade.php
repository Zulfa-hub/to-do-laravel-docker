<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Masuk' }} - {{ config('app.name', 'Astra') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-astra-gradient flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 justify-center mb-2">
                <span class="text-astra-600 text-2xl">&#10022;</span>
                <span class="font-serif text-2xl font-semibold tracking-wide">Astra</span>
            </div>
            <p class="text-xs uppercase tracking-[0.2em] text-astra-600">We Design With Purpose</p>
        </div>

        <div class="card p-8">
            <h1 class="text-2xl font-serif font-semibold mb-1">{{ $title ?? 'Masuk' }}</h1>
            <p class="text-sm text-gray-500 mb-6">{{ $subtitle ?? '' }}</p>

            @if (session('success'))
                <div class="mb-5 rounded-xl bg-astra-50 border border-astra-200 text-astra-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        <p class="text-center text-xs text-gray-500 mt-6">&copy; {{ date('Y') }} Astra To-Do List</p>
    </div>
</body>
</html>
