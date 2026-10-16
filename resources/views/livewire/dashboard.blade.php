<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Pilih Level Pembelajaran</h1>
            <p class="mt-1 text-sm text-gray-600">Pilih level atau tingkat kelas yang ingin Anda akses.</p>
        </div>

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 px-4 sm:px-0">
            @foreach ($levels as $level)
                @php
                    // Cek apakah level ini memiliki relasi ke user yang sedang login
                    $userAccess = $level->users->first();

                    // Cek apakah membership aktif (tidak ada expired_at atau expired_at masih di masa depan)
                    $isExpired =
                        $userAccess &&
                        $userAccess->pivot->membership_expired_at &&
                        \Carbon\Carbon::parse($userAccess->pivot->membership_expired_at)->isPast();

                    $hasActiveAccess = $userAccess && !$isExpired;
                @endphp

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">

                    {{-- Card Header / Banner kecil --}}
                    <div class="p-6 pb-4">
                        <div class="flex justify-between items-start mb-3">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 uppercase tracking-wide">
                                Level
                            </span>

                            {{-- Badge Status Akses --}}
                            @if ($hasActiveAccess)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"></span> Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                    Belum Langganan
                                </span>
                            @endif
                        </div>

                        <h2 class="text-2xl font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">
                            {{ $level->name }}
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            Akses materi, tugas, dan chat interaktif khusus untuk level {{ strtolower($level->name) }}.
                        </p>
                    </div>

                    {{-- Card Footer: Harga & Tombol Aksi --}}
                    <div class="px-6 pb-6 pt-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 block">Harga / Bulan</span>
                            <span
                                class="text-lg font-bold text-gray-900">${{ number_format($level->price ?? 0, 2) }}</span>
                        </div>

                        @if ($hasActiveAccess)
                            {{-- Jika sudah punya akses aktif, arahkan ke halaman utama/chat level --}}
                            <a href="{{ route('level.chat', ['level' => $level->slug]) }}"
                                class="inline-flex items-center justify-center bg-indigo-600 text-white font-medium px-5 py-2.5 rounded-xl hover:bg-indigo-700 shadow-sm shadow-indigo-100 transition">
                                Masuk Akses
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        @else
                            {{-- Jika belum punya akses / expired, arahkan ke halaman beli langganan --}}
                            <a href="{{ route('subscription.checkout', ['level' => $level->slug]) }}"
                                class="inline-flex items-center justify-center bg-emerald-600 text-white font-medium px-5 py-2.5 rounded-xl hover:bg-emerald-700 shadow-sm shadow-emerald-100 transition">
                                Beli Langganan
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</div>
