<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Pengaturan Profil') }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola informasi akun, keamanan kata sandi, dan preferensi akun
                    Anda.</p>
            </div>

            {{-- <!-- Quick Badge Status -->
            <div
                class="hidden sm:flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full text-xs font-medium text-indigo-700">
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                <span>Akun Aktif</span>
            </div> --}}
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Section 1: Informasi Profil -->
        <div
            class="p-6 sm:p-8 bg-white shadow-sm border border-gray-200/80 rounded-xl transition duration-150 hover:shadow-md">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <!-- Section 2: Keamanan Kata Sandi -->
        <div
            class="p-6 sm:p-8 bg-white shadow-sm border border-gray-200/80 rounded-xl transition duration-150 hover:shadow-md">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>

        {{-- <!-- Section 3: Hapus Akun (Zona Bahaya) -->
        <div
            class="p-6 sm:p-8 bg-white shadow-sm border border-red-200/80 rounded-xl transition duration-150 hover:shadow-md">
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </div> --}}
    </div>
</x-app-layout>
