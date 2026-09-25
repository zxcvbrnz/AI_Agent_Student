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

        // Tentukan default route berdasarkan role user
        $defaultRoute = auth()->user()->isAdmin() ? route('admin.users', absolute: false) : route('dashboard', absolute: false);

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

            <p class="text-center text-xs text-gray-600">
                {{ __('Belum punya akun?') }}
                <a class="font-semibold text-indigo-600 hover:text-indigo-800 transition underline ms-1"
                    href="{{ route('register') }}" wire:navigate>
                    {{ __('Daftar akun baru') }}
                </a>
            </p>
        </div>
    </form>
</div>
