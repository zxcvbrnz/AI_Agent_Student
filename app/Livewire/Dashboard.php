<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $levels;

    public function mount()
    {
        $this->levels = \App\Models\Level::all();
    }
    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
