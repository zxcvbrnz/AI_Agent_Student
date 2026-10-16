<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Level;
use Carbon\Carbon;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $addDays = 30; // Default 30 hari
    public $membershipType = 'pro';
    public $selectedLevelId = null; // Level yang dipilih untuk aksi membership

    public function extendMembership($userId)
    {
        $this->validate([
            'selectedLevelId' => 'required|exists:levels,id',
        ], [
            'selectedLevelId.required' => 'Pilih level terlebih dahulu sebelum memperpanjang membership.',
        ]);

        $user = User::findOrFail($userId);
        $levelId = $this->selectedLevelId;

        // Ambil data pivot yang sudah ada untuk level ini
        $currentPivot = $user->levels()->where('level_id', $levelId)->first();

        $expiresAt = $currentPivot ? $currentPivot->pivot->membership_expires_at : null;
        $expiresAtDate = $expiresAt ? Carbon::parse($expiresAt) : null;

        // Jika sudah kedaluwarsa atau belum ada, hitung dari hari ini. Jika masih aktif, tambahkan dari tanggal expired sebelumnya.
        $baseDate = ($expiresAtDate && $expiresAtDate->isFuture())
            ? $expiresAtDate
            : now();

        $newExpiresAt = $baseDate->addDays((int)$this->addDays);

        // Update atau attach ke tabel pivot user_levels
        $user->levels()->syncWithoutDetaching([
            $levelId => [
                'membership_type' => $this->membershipType,
                'membership_expires_at' => $newExpiresAt,
            ]
        ]);

        $level = Level::find($levelId);
        session()->flash('message', "Membership {$user->name} untuk level [{$level->name}] berhasil diperbarui hingga " . $newExpiresAt->format('d M Y H:i'));
    }

    public function setExpired($userId)
    {
        $this->validate([
            'selectedLevelId' => 'required|exists:levels,id',
        ], [
            'selectedLevelId.required' => 'Pilih level terlebih dahulu sebelum menghentikan membership.',
        ]);

        $user = User::findOrFail($userId);
        $levelId = $this->selectedLevelId;

        // Set expired di tabel pivot
        $user->levels()->syncWithoutDetaching([
            $levelId => [
                'membership_type' => 'expired',
                'membership_expires_at' => now()->subSecond(),
            ]
        ]);

        $level = Level::find($levelId);
        session()->flash('message', "Membership {$user->name} untuk level [{$level->name}] telah dihentikan.");
    }

    public function render()
    {
        // Ambil data user beserta relasi levels agar bisa ditampilkan statusnya per level
        $users = User::with('levels')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        $levels = Level::all(); // Untuk dropdown pilihan level pada panel admin

        return view('livewire.admin.user-management', [
            'users' => $users,
            'levels' => $levels,
        ])->layout('layouts.app');
    }
}
