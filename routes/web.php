<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\StudentChat;
use App\Livewire\Admin\UserManagement;
use App\Livewire\SubjectManager;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route Khusus User Terautentikasi & Memiliki Membership Aktif
Route::middleware(['auth', 'active.membership'])->group(function () {
    Route::get('/chat', StudentChat::class)->name('dashboard');
    // Route::get('/chat', StudentChat::class)->name('chat');
});

// Route Tampilan Jika Membership Habis
Route::middleware(['auth'])->get('/membership-expired', function () {
    return view('membership-expired');
})->name('membership.expired');

// Route Khusus Admin
Route::middleware(['auth', 'is.admin'])->prefix('admin')->group(function () {
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/subjects', SubjectManager::class)->name('admin.subjects');
});

require __DIR__ . '/auth.php';
