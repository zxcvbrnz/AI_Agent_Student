<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    @php
        // Mengambil slug level langsung dari URL yang sedang aktif
        $currentLevel = request()->route('level');
    @endphp

    <!-- Mobile Overlay Backdrop -->
    <div x-show="mobileOpen" x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="mobileOpen = false" class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
        style="display: none;"></div>

    <!-- Collapsible Sidebar -->
    <aside
        :class="{
            'translate-x-0 w-64': mobileOpen || (window.innerWidth >= 1024 && sidebarOpen),
            '-translate-x-full': !mobileOpen && (window.innerWidth < 1024 || !sidebarOpen),
            'lg:w-20': !sidebarOpen && window.innerWidth >= 1024,
            'lg:w-64': sidebarOpen && window.innerWidth >= 1024
        }"
        class="fixed top-0 bottom-0 left-0 z-40 bg-white border-r border-gray-200 flex flex-col transition-all duration-300 ease-in-out shadow-sm"
        :style="window.innerWidth < 1024 ? (mobileOpen ? 'transform: translateX(0px); width: 16rem;' :
            'transform: translateX(-100%);') : ''">

        <!-- Header Sidebar: Logo & Close/Open Buttons (Disembunyikan di Mobile, Muncul di Desktop lg:) -->
        <div
            class="h-16 hidden lg:flex items-center justify-between px-4 border-b border-gray-100 shrink-0 overflow-hidden">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                <x-application-logo class="block h-8 w-auto fill-current text-indigo-600 shrink-0" />
                <span
                    class="font-bold text-lg text-gray-800 tracking-wide whitespace-nowrap transition-opacity duration-300"
                    :class="{ 'lg:opacity-0 lg:pointer-events-none lg:hidden': !sidebarOpen, 'lg:opacity-100': sidebarOpen }">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>

            <!-- Toggle Close (Desktop - Muncul saat sidebar terbuka) -->
            <button @click="sidebarOpen = false" x-show="sidebarOpen"
                class="hidden lg:inline-flex p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>

            <!-- Toggle Open (Desktop - Muncul saat sidebar tertutup) -->
            <button @click="sidebarOpen = true" x-show="!sidebarOpen"
                class="hidden lg:inline-flex p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition mx-auto shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Tombol Close khusus Mobile (Opsional jika ingin ditaruh di dalam sidebar karena header utamanya disembunyikan) -->
        <div class="flex lg:hidden items-center justify-between p-4 border-b border-gray-100 shrink-0">
            <span class="font-bold text-gray-800">{{ config('app.name', 'Laravel') }}</span>
            <button @click="mobileOpen = false"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto overflow-x-hidden">

            <!-- SECTION 1: MAIN MENU -->
            <div class="space-y-1">
                <div class="px-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider transition-opacity duration-300"
                    :class="{ 'lg:hidden': !sidebarOpen }">
                    Main Menu
                </div>

                <x-responsive-nav-link
                    href="{{ $currentLevel ? route('level.chat', ['level' => $currentLevel]) : route('dashboard') }}"
                    :active="request()->routeIs('level.chat')" wire:navigate class="rounded-md flex items-center">
                    <div class="flex items-center gap-3 w-full" :class="{ 'justify-center': !sidebarOpen }">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            title="AI Chat">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="whitespace-nowrap transition-opacity duration-300"
                            :class="{ 'lg:hidden': !sidebarOpen }">{{ __('AI Chat') }}</span>
                    </div>
                </x-responsive-nav-link>

                <!-- TOMBOL GANTI LEVEL -->
                <x-responsive-nav-link href="{{ route('dashboard') }}" wire:navigate
                    class="rounded-md flex items-center text-indigo-600 hover:text-indigo-700">
                    <div class="flex items-center gap-3 w-full" :class="{ 'justify-center': !sidebarOpen }">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            title="Ganti Level">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="whitespace-nowrap transition-opacity duration-300 font-medium"
                            :class="{ 'lg:hidden': !sidebarOpen }">{{ __('Ganti Level') }}</span>
                    </div>
                </x-responsive-nav-link>
            </div>

            <!-- SECTION 2: ADMIN MENU -->
            @if (auth()->user()->role === 'admin')
                <div class="space-y-1">
                    <div class="px-3 text-[11px] font-semibold text-indigo-600 uppercase tracking-wider flex items-center justify-between transition-opacity duration-300"
                        :class="{ 'lg:hidden': !sidebarOpen }">
                        <span>Admin Panel</span>
                        <span
                            class="bg-indigo-100 text-indigo-700 text-[10px] px-1.5 py-0.5 rounded font-bold">ADMIN</span>
                    </div>

                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users.*')" wire:navigate
                        class="rounded-md flex items-center">
                        <div class="flex items-center gap-3 w-full" :class="{ 'justify-center': !sidebarOpen }">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                title="Kelola Pengguna">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                :class="{ 'lg:hidden': !sidebarOpen }">{{ __('Kelola Pengguna') }}</span>
                        </div>
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.core-prompts')" :active="request()->routeIs('admin.core-prompts.*')" wire:navigate
                        class="rounded-md flex items-center">
                        <div class="flex items-center gap-3 w-full" :class="{ 'justify-center': !sidebarOpen }">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                title="Kelola Core Prompts">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                :class="{ 'lg:hidden': !sidebarOpen }">{{ __('Kelola Core Prompts') }}</span>
                        </div>
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.subjects')" :active="request()->routeIs('admin.subjects.*')" wire:navigate
                        class="rounded-md flex items-center">
                        <div class="flex items-center gap-3 w-full" :class="{ 'justify-center': !sidebarOpen }">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                title="Kelola Subject">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                :class="{ 'lg:hidden': !sidebarOpen }">{{ __('Kelola Subject') }}</span>
                        </div>
                    </x-responsive-nav-link>
                </div>
            @endif

        </nav>

        <!-- Footer Sidebar: User Profile & Log Out Langsung -->
        <div class="p-3 border-t border-gray-200 bg-gray-50/50 shrink-0 space-y-2">
            <!-- Info User / Tombol Profile -->
            <a href="{{ route('profile') }}" wire:navigate
                class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition text-left overflow-hidden"
                :class="{ 'justify-center': !sidebarOpen }" title="Profile">
                <div
                    class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="truncate transition-opacity duration-300" :class="{ 'lg:hidden': !sidebarOpen }">
                    <div class="font-semibold text-xs text-gray-800 truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="text-[11px] text-gray-500 truncate">
                        {{ auth()->user()->email }}
                    </div>
                </div>
            </a>

            <!-- Tombol Log Out Langsung -->
            <button wire:click="logout"
                class="w-full flex items-center gap-3 p-2 rounded-lg text-red-600 hover:bg-red-50 transition text-left overflow-hidden"
                :class="{ 'justify-center': !sidebarOpen }" title="Log Out">
                <div class="w-9 h-9 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span class="text-xs font-medium whitespace-nowrap transition-opacity duration-300"
                    :class="{ 'lg:hidden': !sidebarOpen }">
                    {{ __('Log Out') }}
                </span>
            </button>
        </div>
    </aside>
</div>
