<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $levels;

    public function mount()
    {
        $user = Auth::user();

        // Ambil semua level, sekaligus load data pivot (relasi user ke level tersebut)
        // Berdasarkan user yang sedang login
        $this->levels = Level::with(['users' => function ($query) use ($user) {
            $query->where('users.id', $user->id);
        }])->get();
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
