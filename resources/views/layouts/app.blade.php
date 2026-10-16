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

        <!-- Tombol Floating Toggle (Desktop & Mobile) jika sidebar tertutup -->
        <div class="fixed top-4 left-4 z-30" x-show="!sidebarOpen">
            <button @click="sidebarOpen = true"
                class="hidden lg:inline-flex items-center justify-center p-2.5 rounded-lg bg-white shadow-md border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Header Bar (Tampil hanya di Layar HP) -->
        <div
            class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 z-30 px-4 flex items-center justify-between">
            <button @click="mobileOpen = !mobileOpen"
                class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                <x-application-logo class="block h-7 w-auto fill-current text-gray-800" />
                <span class="font-bold text-base text-gray-800 tracking-wide">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>

            <div class="w-8"></div>
        </div>

        <!-- Livewire Sidebar Component -->
        @if (!request()->routeIs('dashboard'))
            <livewire:layout.navigation />
        @endif

        <!-- Main Content Area (Dynamic Margin Padding) -->
        <div :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-20'"
            class="pt-16 lg:pt-0 flex flex-col flex-1 transition-all duration-300 ease-in-out">

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
