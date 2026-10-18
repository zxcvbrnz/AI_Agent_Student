<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            } else {
                // Buat user baru jika belum terdaftar
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(24)),
                ]);
            }

            Auth::login($user);

            // Tentukan default redirect berdasarkan role atau kepemilikan level aktif
            if ($user->isAdmin()) {
                $defaultRoute = route('admin.users', absolute: false);
            } else {
                // Cek level aktif yang dimiliki user
                $activeLevel = $user->levels()
                    ->where(function ($query) {
                        $query->whereNull('membership_expires_at')
                            ->orWhere('membership_expires_at', '>', now());
                    })
                    ->first();

                if ($activeLevel) {
                    $defaultRoute = route('level.chat', ['level' => $activeLevel->slug], absolute: false);
                } else {
                    $defaultRoute = route('dashboard', absolute: false);
                }
            }

            return redirect()->intended($defaultRoute);
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
