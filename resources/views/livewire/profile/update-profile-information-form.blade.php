<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Dispatch event untuk mengupdate nama di topbar secara langsung
        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
        <div
            class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <h2 class="text-base font-bold text-gray-900">
                {{ __('Informasi Profil') }}
            </h2>
            <p class="text-xs text-gray-500">
                {{ __('Perbarui informasi identitas diri dan alamat email akun Anda.') }}
            </p>
        </div>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-5">
        <!-- Preview Avatar / Inisial User -->
        <div class="flex items-center gap-4 p-3 bg-gray-50 border border-gray-100 rounded-lg">
            <div
                class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-base uppercase shrink-0 shadow-sm">
                {{ substr($name ?: 'U', 0, 2) }}
            </div>
            <div>
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Inisial Avatar</div>
                <div class="text-sm font-medium text-gray-800">{{ $name ?: 'User Name' }}</div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-semibold text-gray-700" />
            <x-text-input wire:model="name" id="name" name="name" type="text"
                class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-1.5 text-xs" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-semibold text-gray-700" />
            <x-text-input wire:model="email" id="email" name="email" type="email"
                class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                required autocomplete="username" />
            <x-input-error class="mt-1.5 text-xs" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-xs text-amber-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ __('Alamat email Anda belum diverifikasi.') }}</span>
                    </p>
                    <button wire:click.prevent="sendVerification"
                        class="mt-2 text-xs text-indigo-600 hover:text-indigo-800 font-semibold underline focus:outline-none">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button
                class="px-5 py-2.5 text-xs rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition">
                {{ __('Simpan Perubahan') }}
            </x-primary-button>

            <x-action-message class="text-xs font-medium text-emerald-600 flex items-center gap-1" on="profile-updated">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ __('Tersimpan.') }}</span>
            </x-action-message>
        </div>
    </form>
</section>
