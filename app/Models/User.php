<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'membership_type',
        'membership_expires_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'membership_expires_at' => 'datetime',
    ];

    // Otomatis kasih Free Trial 1 Hari saat User baru terdaftar
    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->role) {
                $user->role = 'user';
            }
            if (!$user->membership_expires_at) {
                $user->membership_type = 'free_trial';
                $user->membership_expires_at = now()->addDay(); // Free Trial 1 Hari (24 Jam)
            }
        });
    }

    // Helper: Cek apakah user adalah Admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Helper: Cek apakah membership/trial masih aktif
    public function hasActiveMembership(): bool
    {
        if ($this->isAdmin()) {
            return true; // Admin selalu aktif
        }

        if (!$this->membership_expires_at) {
            return false;
        }

        return now()->lessThanOrEqualTo($this->membership_expires_at);
    }
}
