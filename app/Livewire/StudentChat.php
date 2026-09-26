<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Subject;
use App\Models\ChatHistory; // Import Model ChatHistory
use App\Services\GeminiService;
use Illuminate\Support\Facades\Storage;

class StudentChat extends Component
{
    use WithFileUploads;

    public $subjects;
    public $selectedSubjectId;
    public $userMessage = '';
    public $files = [];
    public $chatHistory = [];

    public function mount()
    {
        $this->subjects = Subject::all();
        if ($this->subjects->isNotEmpty()) {
            $this->selectedSubjectId = $this->subjects->first()->id;
            $this->loadChatHistory(); // Load riwayat chat saat pertama dibuka
        }
    }

    public function selectSubject($subjectId)
    {
        $this->selectedSubjectId = $subjectId;
        $this->reset(['userMessage', 'files']);
        $this->loadChatHistory(); // Load riwayat chat sesuai mata pelajaran yang dipilih
    }

    public function loadChatHistory()
    {
        $this->chatHistory = [];

        $savedChats = ChatHistory::where('user_id', auth()->id())
            ->where('subject_id', $this->selectedSubjectId)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($savedChats as $chat) {
            // Chat User dari database
            $this->chatHistory[] = [
                'role'   => 'user',
                'text'   => $chat->user_message,
                'files'  => $chat->files ?? [],
                'is_new' => false, // <-- Bukan pesan baru
            ];

            // Balasan AI dari database
            $this->chatHistory[] = [
                'role'   => 'ai',
                'text'   => $chat->ai_response,
                'files'  => [],
                'is_new' => false, // <-- Sembunyikan animasi typing saat refresh
            ];
        }
    }

    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files);
        }
    }

    public function sendMessage(GeminiService $gemini, string $messageText = '')
    {
        $inputPrompt = trim($messageText) ?: trim($this->userMessage);

        if (empty($inputPrompt) && empty($this->files)) {
            $this->addError('userMessage', 'Pesan atau file tidak boleh kosong.');
            return;
        }

        $this->validate([
            'files.*' => 'nullable|file|max:10240',
        ]);

        $subject = Subject::find($this->selectedSubjectId);
        if (!$subject) return;

        $subjectFiles = $subject->files ?? [];

        $finalPrompt = $inputPrompt ?: (!empty($this->files) ? 'Tolong analisa file/gambar ini.' : '');

        // 1. Simpan file secara permanen ke folder storage disk public
        $storedFiles = [];
        foreach ($this->files as $file) {
            $path = $file->store('chat-attachments', 'public');
            $isImage = str_starts_with($file->getMimeType(), 'image/');

            $storedFiles[] = [
                'url'  => Storage::url($path),
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'type' => $isImage ? 'image' : 'file',
            ];
        }

        // Tampilkan dulu di UI secara cepat
        $this->chatHistory[] = [
            'role'  => 'user',
            'text'  => $finalPrompt,
            'files' => $storedFiles,
        ];

        $previousHistory = array_slice($this->chatHistory, 0, -1);

        try {
            // 2. Minta jawaban dari Gemini AI
            $aiResponse = $gemini->ask(
                systemPrompt: $subject->system_prompt . " Pastikan untuk TIDAK menjawab jika pertanyaannya tidak relevan dengan " . $subject->name . ".",
                userMessage: $finalPrompt,
                chatHistory: $previousHistory,
                files: $this->files,
                subjectFiles: $subjectFiles
            );

            // 3. SIMPAN KE DATABASE PERMANEN
            ChatHistory::create([
                'user_id'      => auth()->id(),
                'subject_id'   => $this->selectedSubjectId,
                'user_message' => $finalPrompt,
                'ai_response'  => $aiResponse,
                'files'        => $storedFiles, // Disimpan sebagai array JSON berkat $casts
            ]);

            // 4. Update tampilan array chat dengan balasan AI
            $this->chatHistory[] = [
                'role'  => 'ai',
                'text'  => $aiResponse,
                'files' => [],
                'is_new' => true,
            ];

            $this->reset(['userMessage', 'files']);
        } catch (\Exception $e) {
            array_pop($this->chatHistory);
            $this->addError('userMessage', 'Gagal terhubung ke AI: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.student-chat', [
            'currentSubject' => Subject::find($this->selectedSubjectId)
        ])->layout('layouts.app');
    }
}