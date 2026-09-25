<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
        <div
            class="w-10 h-10 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <div>
            <h2 class="text-base font-bold text-gray-900">
                {{ __('Pembaruan Kata Sandi') }}
            </h2>
            <p class="text-xs text-gray-500">
                {{ __('Pastikan akun Anda menggunakan kata sandi yang kuat dan acak untuk keamanan.') }}
            </p>
        </div>
    </header>

    <form wire:submit="updatePassword" class="space-y-5">
        <div>
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')"
                class="text-xs font-semibold text-gray-700" />
            <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password"
                type="password"
                class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-1.5 text-xs" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')"
                class="text-xs font-semibold text-gray-700" />
            <x-text-input wire:model="password" id="update_password_password" name="password" type="password"
                class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')"
                class="text-xs font-semibold text-gray-700" />
            <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation"
                name="password_confirmation" type="password"
                class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button
                class="px-5 py-2.5 text-xs rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition">
                {{ __('Ubah Kata Sandi') }}
            </x-primary-button>

            <x-action-message class="text-xs font-medium text-emerald-600 flex items-center gap-1"
                on="password-updated">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ __('Kata sandi berhasil diperbarui.') }}</span>
            </x-action-message>
        </div>
    </form>
</section>
