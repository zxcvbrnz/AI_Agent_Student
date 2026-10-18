<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();

        // Jika admin, arahkan ke halaman manajemen user admin (atau sesuaikan prefix adminnya)
        if ($user->isAdmin()) {
            $defaultRoute = route('admin.users', absolute: false);
        } else {
            // Ambil level pertama yang memiliki membership aktif atau level yang dimiliki user
            // Menggunakan relasi levels() yang sudah kita buat sebelumnya
            $activeLevel = $user
                ->levels()
                ->where(function ($query) {
                    $query->whereNull('membership_expires_at')->orWhere('membership_expires_at', '>', now());
                })
                ->first();

            // Jika user memiliki level aktif, arahkan ke route level chat mereka (misal: /tes/chat)
            if ($activeLevel) {
                $defaultRoute = route('level.chat', ['level' => $activeLevel->slug], absolute: false);
            } else {
                // Jika tidak ada level aktif, arahkan ke dashboard pemilihan level
                $defaultRoute = route('dashboard', absolute: false);
            }
        }

        $this->redirectIntended(default: $defaultRoute, navigate: true);
    }
}; ?>

<div>
    <!-- Title Section -->
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-gray-900 tracking-tight">Selamat Datang Kembali</h1>
        <p class="text-xs text-gray-500 mt-1">Masukkan kredensial akun Anda untuk masuk</p>
    </div>

    <!-- Session Status Notification -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-semibold text-gray-700" />
            <x-text-input wire:model="form.email" id="email"
                class="block mt-1 w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                type="email" name="email" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs" />
        </div>

        <!-- Password & Forgot Link -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Kata Sandi')" class="text-xs font-semibold text-gray-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition"
                        href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Lupa kata sandi?') }}
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password"
                class="block mt-1 w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4"
                    name="remember">
                <span class="ms-2 text-xs text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <!-- Action Buttons & Link -->
        <div class="pt-2 space-y-4">
            <x-primary-button
                class="w-full justify-center py-2.5 text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition">
                {{ __('Masuk') }}
            </x-primary-button>

            <!-- Divider / Pemisah (Atau masuk dengan) -->
            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="flex-shrink mx-4 text-xs text-gray-400 uppercase tracking-wider">Atau</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <!-- Tombol Login Google Profesional -->
            <div>
                <a href="{{ route('google.login') }}"
                    class="flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <!-- Ikon SVG Google Resmi -->
                    <svg class="h-4 w-4" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z" />
                        <path fill="#34A853"
                            d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.13 0-5.78-2.11-6.73-4.96H1.19v3.15C3.17 21.32 7.31 24 12 24z" />
                        <path fill="#FBBC05"
                            d="M5.27 14.24c-.25-.72-.38-1.49-.38-2.24s.13-1.52.38-2.24V6.6H1.19C.43 8.13 0 9.87 0 12s.43 3.87 1.19 5.4l4.08-3.16z" />
                        <path fill="#EA4335"
                            d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.17 2.68 1.19 6.6l4.08 3.16c.95-2.85 3.6-4.96 6.73-4.96z" />
                    </svg>
                    <span>{{ __('Masuk dengan Google') }}</span>
                </a>
            </div>

            <p class="text-center text-xs text-gray-600 pt-2">
                {{ __('Belum punya akun?') }}
                <a class="font-semibold text-indigo-600 hover:text-indigo-800 transition underline ms-1"
                    href="{{ route('register') }}" wire:navigate>
                    {{ __('Daftar akun baru') }}
                </a>
            </p>
        </div>
    </form>
</div>
