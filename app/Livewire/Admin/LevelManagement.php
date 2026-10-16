<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Level;
use Illuminate\Support\Str;

class LevelManagement extends Component
{
    use WithPagination;

    public $search = '';

    // Properti Form Input / Modal
    public $levelId = null;
    public $name = '';
    public $slug = '';
    public $prompt = '';
    public $price = '';
    public $color = 'indigo'; // Default color scheme

    public $isModalOpen = false;
    public $isEditMode = false;

    // Validasi input
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:levels,slug,' . $this->levelId,
            'prompt' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'color' => 'required|string|max:50',
        ];
    }

    // Auto-generate slug ketika nama berubah (saat mode tambah)
    public function updatedName($value)
    {
        if (!$this->isEditMode) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEditMode = true;
        $this->levelId = $id;

        $level = Level::findOrFail($id);
        $this->name = $level->name;
        $this->slug = $level->slug;
        $this->prompt = $level->prompt;
        $this->price = $level->price;
        $this->color = $level->color ?? 'indigo';

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->levelId = null;
        $this->name = '';
        $this->slug = '';
        $this->prompt = '';
        $this->price = '';
        $this->color = 'indigo';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        Level::updateOrCreate(
            ['id' => $this->levelId],
            [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'prompt' => $this->prompt,
                'price' => $this->price,
                'color' => $this->color,
            ]
        );

        session()->flash('message', $this->isEditMode ? 'Level berhasil diperbarui.' : 'Level baru berhasil ditambahkan.');

        $this->closeModal();
    }

    public function delete($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        session()->flash('message', 'Level berhasil dihapus.');
    }

    public function render()
    {
        $levels = Level::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.level-management', [
            'levels' => $levels
        ])->layout('layouts.app');
    }
}
