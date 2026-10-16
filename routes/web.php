<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\StudentChat;
use App\Livewire\Admin\UserManagement;
use App\Livewire\ManageCorePrompts;
use App\Livewire\SubjectManager;
use App\Http\Controllers\Auth\GoogleController;
use App\Livewire\Admin\LevelManagement;

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route Khusus User Terautentikasi & Memiliki Membership Aktif

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(['dynamic.level'])
        ->prefix('{level}')
        ->name('level.')
        ->group(function () {

            // URL akan menjadi: /{level}/dashboard (contoh: /sd/dashboard, /kuliah/dashboard)
            // Nama route menjadi: level.dashboard
            Route::get('/chat', StudentChat::class)->name('chat');
        });

    // Route::middleware(['active.membership'])->group(function () {
    //     Route::get('/chat', StudentChat::class)->name('chat');
    // });
});

// Route Tampilan Jika Membership Habis
Route::middleware(['auth'])->get('/membership-expired', function () {
    return view('membership-expired');
})->name('membership.expired');

// Route Khusus Admin
Route::middleware(['auth', 'is.admin'])->prefix('admin')->group(function () {
    Route::get('/manage-users', UserManagement::class)->name('admin.users');
    Route::get('/manage-levels', LevelManagement::class)->name('admin.level');
    Route::get('/core-prompts', ManageCorePrompts::class)->name('admin.core-prompts');
    Route::get('/subjects', SubjectManager::class)->name('admin.subjects');
});

require __DIR__ . '/auth.php';
