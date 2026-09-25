<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-slate-50 selection:bg-indigo-500 selection:text-white">
    <div
        class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Ambient Background Glow -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-100/60 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-100/60 rounded-full blur-3xl pointer-events-none">
        </div>

        <!-- Header / Branding -->
        <div class="mb-6 text-center z-10">
            <a href="/" wire:navigate class="inline-flex flex-col items-center gap-2 group">
                <div
                    class="p-3 bg-white border border-gray-200/80 rounded-2xl shadow-sm group-hover:scale-105 transition duration-200">
                    <x-application-logo class="w-10 h-10 fill-current text-indigo-600" />
                </div>
                <span class="font-bold text-xl text-gray-800 tracking-tight">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>
        </div>

        <!-- Card Form Container -->
        <div
            class="w-full sm:max-w-md bg-white border border-gray-200/80 shadow-xl shadow-gray-200/50 rounded-2xl overflow-hidden p-6 sm:p-8 z-10">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-400 z-10">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
