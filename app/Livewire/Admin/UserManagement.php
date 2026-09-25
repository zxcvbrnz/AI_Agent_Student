<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Carbon\Carbon;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedUserId = null;
    public $addDays = 30; // Default 1 bulan (30 hari)
    public $membershipType = 'pro';

    public function extendMembership($userId)
    {
        $user = User::findOrFail($userId);

        // Jika sudah kedaluwarsa, hitung dari hari ini. Jika masih aktif, tambahkan dari tanggal expired sebelumnya.
        $baseDate = ($user->membership_expires_at && $user->membership_expires_at->isFuture())
            ? $user->membership_expires_at
            : now();

        $user->update([
            'membership_type' => $this->membershipType,
            'membership_expires_at' => $baseDate->addDays((int)$this->addDays),
        ]);

        session()->flash('message', "Membership {$user->name} berhasil diperbarui hingga " . $user->membership_expires_at->format('d M Y H:i'));
    }

    public function setExpired($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'membership_type' => 'expired',
            'membership_expires_at' => now()->subSecond(),
        ]);

        session()->flash('message', "Membership {$user->name} telah dihentikan.");
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
