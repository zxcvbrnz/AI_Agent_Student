<?php

namespace App\Livewire;

use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectManager extends Component
{
    use WithPagination;

    public $name;
    public $icon;
    public $system_prompt;
    public $subjectId = null;

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

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        Subject::updateOrCreate(
            ['id' => $this->subjectId],
            [
                'name' => $this->name,
                'icon' => $this->icon,
                'system_prompt' => $this->system_prompt,
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
