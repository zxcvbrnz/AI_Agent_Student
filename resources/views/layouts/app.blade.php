<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- KaTeX CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>

    <!-- Marked.js -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 min-h-screen">
    <!-- Outer Wrapper dengan State Alpine.js Utama -->
    <div x-data="{ sidebarOpen: true, mobileOpen: false }" class="min-h-screen flex flex-col">

        <!-- Tombol Floating Toggle (Desktop) jika sidebar tertutup -->
        <div class="fixed top-4 left-4 z-30" x-show="!sidebarOpen">
            <button @click="sidebarOpen = true"
                class="hidden lg:inline-flex items-center justify-center p-2.5 rounded-lg bg-white shadow-md border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Tombol Floating kecil di Mobile untuk Membuka Sidebar (Opsional agar user tetap bisa buka sidebar di HP) -->
        <div class="fixed top-3 left-3 z-30 lg:hidden" x-show="!mobileOpen">
            <button @click="mobileOpen = true"
                class="inline-flex items-center justify-center p-2 rounded-xl bg-white/80 backdrop-blur-md shadow-sm border border-gray-200 text-gray-700 hover:bg-white focus:outline-none transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Livewire Sidebar Component -->
        @if (!request()->routeIs('dashboard'))
            <livewire:layout.navigation />
        @endif

        <!-- Main Content Area (Padding top di HP dihilangkan jadi pt-0) -->
        <div @if (!request()->routeIs('dashboard')) :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-20'" @endif
            class="pt-0 flex flex-col flex-1 transition-all duration-300 ease-in-out">

            <!-- Page Header (Optional) -->
            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 min-h-0">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>

</html>
