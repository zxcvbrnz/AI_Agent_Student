<?php

namespace App\Livewire;

use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // <-- Tambahan
use Illuminate\Support\Facades\Storage;

class SubjectManager extends Component
{
    use WithPagination, WithFileUploads; // <-- Tambahan

    public $name;
    public $icon;
    public $system_prompt;
    public $subjectId = null;

    public $files = []; // <-- File baru yang diunggah
    public $existingFiles = []; // <-- File yang sudah ada di DB

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $subjectIdBeingDeleted = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:50',
            'system_prompt' => 'required|string',
            'files.*' => 'nullable|file|max:10240', // Maks 10MB per file
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $this->subjectId = $id;
        $this->name = $subject->name;
        $this->icon = $subject->icon;
        $this->system_prompt = $subject->system_prompt;
        $this->existingFiles = $subject->files ?? []; // <-- Load file lama

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function removeExistingFile($index)
    {
        if (isset($this->existingFiles[$index])) {
            unset($this->existingFiles[$index]);
            $this->existingFiles = array_values($this->existingFiles);
        }
    }

    public function removeNewFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files);
        }
    }

    public function store()
    {
        $this->validate();

        // Simpan file baru
        $uploadedFiles = [];
        foreach ($this->files as $file) {
            $path = $file->store('subject-attachments', 'public');
            $uploadedFiles[] = [
                'url' => Storage::url($path),
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
            ];
        }

        // Gabungkan file lama & file baru
        $allFiles = array_merge($this->existingFiles, $uploadedFiles);

        Subject::updateOrCreate(
            ['id' => $this->subjectId],
            [
                'name' => $this->name,
                'icon' => $this->icon,
                'system_prompt' => $this->system_prompt,
                'files' => $allFiles, // <-- Simpan array file
            ]
        );

        session()->flash('message', $this->subjectId ? 'Subject berhasil diperbarui!' : 'Subject berhasil ditambahkan!');

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->subjectIdBeingDeleted = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->subjectIdBeingDeleted) {
            $subject = Subject::find($this->subjectIdBeingDeleted);
            if ($subject && !empty($subject->files)) {
                foreach ($subject->files as $f) {
                    if (isset($f['path'])) {
                        Storage::disk('public')->delete($f['path']);
                    }
                }
            }
            Subject::destroy($this->subjectIdBeingDeleted);
            session()->flash('message', 'Subject berhasil dihapus!');
        }

        $this->isDeleteModalOpen = false;
        $this->subjectIdBeingDeleted = null;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->icon = '';
        $this->system_prompt = '';
        $this->files = [];
        $this->existingFiles = [];
        $this->subjectId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $subjects = Subject::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.subject-manager', [
            'subjects' => $subjects,
        ])->layout('layouts.app');
    }
}