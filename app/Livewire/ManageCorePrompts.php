<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ManageCorePrompts extends Component
{
    public $promt = '';

    public function mount()
    {
        // Ambil record pertama (single data)
        $promptData = DB::table('core_promts')->first();

        if ($promptData) {
            $this->promt = $promptData->promt;
        }
    }

    protected function rules()
    {
        return [
            'promt' => 'required|string|min:5',
        ];
    }

    protected $messages = [
        'promt.required' => 'Isi prompt wajib diisi.',
        'promt.min' => 'Isi prompt minimal 5 karakter.',
    ];

    public function save()
    {
        $this->validate();

        $existing = DB::table('core_promts')->first();

        if ($existing) {
            // Update record yang sudah ada (ID pertama)
            DB::table('core_promts')
                ->where('id', $existing->id)
                ->update([
                    'promt' => $this->promt,
                    'updated_at' => now(),
                ]);
        } else {
            // Buat record baru jika tabel masih kosong
            DB::table('core_promts')->insert([
                'promt' => $this->promt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        session()->flash('message', 'Core prompt berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.manage-core-prompts')->layout('layouts.app');
    }
}
