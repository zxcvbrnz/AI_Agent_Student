<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EduAI Agent') }} - AI Tutor Pintar Pelajar</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Vite Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md shadow-indigo-600/20">
                        AI
                    </div>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900">
                        EduAI<span class="text-indigo-600">.Agent</span>
                    </span>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="#fitur" class="hover:text-indigo-600 transition-colors">Fitur Utama</a>
                    <a href="#prompts" class="hover:text-indigo-600 transition-colors">Mata Pelajaran</a>
                    <a href="#harga" class="hover:text-indigo-600 transition-colors">Membership</a>
                    <a href="#faq" class="hover:text-indigo-600 transition-colors">FAQ</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition-all shadow-md shadow-indigo-600/20">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                                Masuk
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition-all shadow-md shadow-indigo-600/20">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-20 pb-16 md:pt-28 md:pb-24 overflow-hidden">
        <div
            class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-indigo-200/40 blur-[100px] rounded-full pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold mb-6">
                ✨ AI Agent Khusus Pelajar & Mahasiswa
            </span>
            <h1
                class="text-4xl sm:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight max-w-4xl mx-auto">
                Tanyakan Apa Saja Tentang Pelajaranmu pada <span class="text-indigo-600">AI Tutor 24/7</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Pahami materi sulit, selesaikan latihan soal, dan pelajari setiap konsep mata pelajaran dengan bantuan
                prompt AI terstruktur.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-lg shadow-indigo-600/25 transition-all hover:scale-105">
                    Mulai Coba Gratis 1 Hari
                </a>
                <a href="#prompts"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold shadow-sm transition-all">
                    Lihat Contoh Prompt Mapel
                </a>
            </div>

            <!-- Stats -->
            <div
                class="mt-14 pt-8 border-t border-slate-200 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto text-slate-600 text-sm">
                <div>
                    <div class="text-2xl font-bold text-slate-900">10+</div>
                    <div>Mata Pelajaran</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900">Prompt Khusus</div>
                    <div>Dioptimalkan per Topik</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900">24/7</div>
                    <div>Akses Kapan Saja</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900">Murah</div>
                    <div>Membership Bulanan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcase Prompt Per Mata Pelajaran -->
    <section id="prompts" class="py-20 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Prompt Khusus Tiap Mata Pelajaran</h2>
                <p class="mt-3 text-slate-600">Setiap mata pelajaran dilengkapi dengan sistem prompt terpilih agar
                    jawaban AI lebih akurat dan mudah dipahami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div
                    class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-indigo-300 hover:shadow-md transition-all">
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xl mb-4">
                        📐
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Matematika</h3>
                    <p class="text-slate-600 text-sm mb-4">Membantu menyelesaikan soal hitungan beserta langkah
                        penyelesaian yang rinci.</p>
                    <div class="p-3 rounded-lg bg-white text-xs font-mono text-indigo-700 border border-slate-200">
                        "Jelaskan cara kerja rumus turunan ini step-by-step..."
                    </div>
                </div>

                <!-- Card 2 -->
                <div
                    class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-indigo-300 hover:shadow-md transition-all">
                    <div
                        class="w-12 h-12 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center font-bold text-xl mb-4">
                        🌍
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Bahasa Inggris</h3>
                    <p class="text-slate-600 text-sm mb-4">Latihan percakapan, perbaikan grammar, dan penulisan essay.
                    </p>
                    <div class="p-3 rounded-lg bg-white text-xs font-mono text-violet-700 border border-slate-200">
                        "Koreksi grammar paragraf ini dan berikan saran kosakata..."
                    </div>
                </div>

                <!-- Card 3 -->
                <div
                    class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-indigo-300 hover:shadow-md transition-all">
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xl mb-4">
                        💻
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Informatika & Coding</h3>
                    <p class="text-slate-600 text-sm mb-4">Membantu debug error, penjelasan algoritma, serta logika
                        pemrograman.</p>
                    <div class="p-3 rounded-lg bg-white text-xs font-mono text-emerald-700 border border-slate-200">
                        "Cari kesalahan sintaks pada kode PHP ini dan jelaskan..."
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section (Membership) -->
    <section id="harga" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Paket Membership Terjangkau</h2>
                <p class="mt-3 text-slate-600">Nikmati Free Trial 1 Hari atau langsung langganan bulanan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Trial -->
                <div class="p-8 rounded-2xl bg-white border border-slate-200 flex flex-col justify-between shadow-sm">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Uji Coba</span>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">Free Trial 1 Hari</h3>
                        <p class="text-slate-600 text-sm mt-1">Otomatis aktif saat baru mendaftar.</p>
                        <div class="my-6">
                            <span class="text-4xl font-extrabold text-slate-900">Rp 0</span>
                            <span class="text-slate-500">/ 24 jam</span>
                        </div>
                        <ul class="space-y-3 text-sm text-slate-600 mb-8">
                            <li class="flex items-center gap-2">✓ Akses penuh selama 24 Jam</li>
                            <li class="flex items-center gap-2">✓ Bebas tanya semua mata pelajaran</li>
                            <li class="flex items-center gap-2">✓ Menggunakan model AI Gemini terbaru</li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                        class="w-full py-3 text-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold transition-all">
                        Daftar Trial Gratis
                    </a>
                </div>

                <!-- Pro Student Pass -->
                <div
                    class="p-8 rounded-2xl bg-gradient-to-b from-indigo-50/70 to-white border-2 border-indigo-600 flex flex-col justify-between shadow-xl relative">
                    <span
                        class="absolute -top-3.5 right-6 px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">
                        Rekomendasi
                    </span>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Akses Penuh</span>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">Pro Student Pass</h3>
                        <p class="text-slate-600 text-sm mt-1">Perpanjang masa aktif belajar setiap bulan.</p>
                        <div class="my-6">
                            <span class="text-4xl font-extrabold text-slate-900">Rp 29.000</span>
                            <span class="text-slate-500">/ bulan</span>
                        </div>
                        <ul class="space-y-3 text-sm text-slate-700 mb-8">
                            <li class="flex items-center gap-2 text-indigo-900 font-medium">✓ Unlimited Pertanyaan
                                (Tanpa Batas)</li>
                            <li class="flex items-center gap-2 text-indigo-900 font-medium">✓ Akses Prompt Khusus Semua
                                Mapel</li>
                            <li class="flex items-center gap-2 text-indigo-900 font-medium">✓ Penjelasan Rinci &
                                Step-by-Step</li>
                            <li class="flex items-center gap-2 text-indigo-900 font-medium">✓ Respon Cepat Gemini AI
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                        class="w-full py-3 text-center rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-all shadow-md shadow-indigo-600/20">
                        Mulai Langganan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-white border-t border-slate-200 text-center text-slate-500 text-sm">
        <p>&copy; {{ date('Y') }} EduAI Agent. All rights reserved.</p>
    </footer>

</body>

</html>
