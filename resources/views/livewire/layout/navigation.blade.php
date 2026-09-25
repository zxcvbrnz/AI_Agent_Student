<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ sidebarOpen: true, mobileOpen: false }">
    <!-- ========================================== -->
    <!-- 1. TOP NAVIGATION BAR (HEADER ATAS)        -->
    <!-- ========================================== -->
    <header
        class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-30 h-16 flex items-center justify-between px-4 sm:px-6">
        <!-- Left Side: Toggle Button & Logo/Brand -->
        <div class="flex items-center gap-4">
            <!-- Tombol Toggle Sidebar (Desktop) -->
            <button @click="sidebarOpen = !sidebarOpen"
                class="hidden lg:inline-flex p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Tombol Toggle Sidebar (Mobile) -->
            <button @click="mobileOpen = !mobileOpen"
                class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Brand / Logo Header -->
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                <span class="font-bold text-lg text-gray-800 tracking-wide hidden sm:inline-block">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>
        </div>

        <!-- Right Side: User Name, Email, & Profile Dropdown -->
        <div class="flex items-center gap-3">
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center gap-3 px-3 py-1.5 border border-gray-200 rounded-full sm:rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition">
                        <!-- Avatar / Inisial User -->
                        <div
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>

                        <!-- Nama & Email di Pojok Kanan Atas -->
                        <div class="text-left hidden sm:block">
                            <div class="font-semibold text-gray-800 text-xs leading-tight" x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="text-[11px] text-gray-500 font-normal leading-tight">
                                {{ auth()->user()->email }}
                            </div>
                        </div>

                        <!-- Dropdown Icon -->
                        <svg class="w-4 h-4 text-gray-400 ms-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Tampilan HP untuk Nama & Email (jika layar kecil) -->
                    <div class="px-4 py-2 border-b border-gray-100 sm:hidden">
                        <div class="font-semibold text-xs text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</div>
                    </div>

                    <div class="px-4 py-1.5 border-b border-gray-100">
                        <span
                            class="text-[10px] uppercase font-bold px-2 py-0.5 rounded {{ auth()->user()->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                            Role: {{ auth()->user()->role ?? 'User' }}
                        </span>
                    </div>

                    <x-dropdown-link :href="route('profile')" wire:navigate class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ __('Profile') }}</span>
                    </x-dropdown-link>

                    <button wire:click="logout" class="w-full text-start">
                        <x-dropdown-link
                            class="text-red-600 hover:text-red-700 hover:bg-red-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>{{ __('Log Out') }}</span>
                        </x-dropdown-link>
                    </button>
                </x-slot>
            </x-dropdown>
        </div>
    </header>

    <!-- ========================================== -->
    <!-- 2. MOBILE OVERLAY BACKDROP                 -->
    <!-- ========================================== -->
    <div x-show="mobileOpen" x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="mobileOpen = false"
        class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" style="display: none;"></div>

    <!-- ========================================== -->
    <!-- 3. SIDEBAR NAVIGATION (BISA BUKA TUTUP)    -->
    <!-- ========================================== -->
    <aside
        :class="{
            'translate-x-0': mobileOpen,
            '-translate-x-full': !mobileOpen,
            'lg:translate-x-0 lg:w-64': sidebarOpen,
            'lg:-translate-x-full lg:w-0': !sidebarOpen
        }"
        class="fixed top-16 bottom-0 left-0 z-20 w-64 bg-white border-r border-gray-200 flex flex-col transition-all duration-300 ease-in-out">

        <!-- Menu Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto">

            <!-- SECTION 1: MAIN MENU -->
            <div class="space-y-1">
                <div class="px-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                    Main Menu
                </div>

                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="rounded-md">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>{{ __('AI Chat') }}</span>
                    </div>
                </x-responsive-nav-link>
            </div>

            <!-- SECTION 2: USER MENU -->
            {{-- <div class="space-y-1">
                <div class="px-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                    Menu Saya
                </div>

                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('courses.*')" wire:navigate class="rounded-md">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>{{ __('Kelas Saya') }}</span>
                    </div>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('transactions.*')" wire:navigate class="rounded-md">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>{{ __('Riwayat Transaksi') }}</span>
                    </div>
                </x-responsive-nav-link>
            </div> --}}

            <!-- SECTION 3: ADMIN MENU -->
            @if (auth()->user()->role === 'admin')
                <div class="space-y-1">
                    <div
                        class="px-3 text-[11px] font-semibold text-indigo-600 uppercase tracking-wider flex items-center justify-between">
                        <span>Admin Panel</span>
                        <span
                            class="bg-indigo-100 text-indigo-700 text-[10px] px-1.5 py-0.5 rounded font-bold">ADMIN</span>
                    </div>

                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users.*')" wire:navigate class="rounded-md">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>{{ __('Kelola Pengguna') }}</span>
                        </div>
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.subjects')" :active="request()->routeIs('admin.subjects.*')" wire:navigate class="rounded-md">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>{{ __('Kelola Subject') }}</span>
                        </div>
                    </x-responsive-nav-link>
                </div>
            @endif

        </nav>
    </aside>
</div>
