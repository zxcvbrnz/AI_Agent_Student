<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Blob;
use Gemini\Data\Content;
use Gemini\Enums\MimeType;
use Gemini\Enums\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeminiService
{
    protected array $fallbackModels = [
        'gemini-3.5-flash',
        'gemini-3.5-flash-lite',
        'gemini-3.1-flash-lite',
        'gemini-2.5-flash',
        'gemini-2.5-flash-lite',
        'gemini-2.5-pro',
        'gemini-1.5-flash',
        'gemini-1.5-pro',
    ];

    public function ask(
        ?string $systemPrompt = null,
        ?string $userMessage = null,
        array $chatHistory = [],
        array $files = [],
        ?string $subjectName = null,
        array $subjectFiles = [] // <-- Tambahan parameter file subject
    ): string {
        if (empty($userMessage) && empty($files) && empty($subjectFiles)) {
            return 'Pesan atau gambar tidak boleh kosong.';
        }

        // --- TAMBAHAN CORE PROMPT SINGLE DATA ---
        // 1. Ambil data core prompt dari database
        $corePromptData = DB::table('core_promts')->first();
        $corePromptText = $corePromptData ? trim($corePromptData->promt) : '';

        if (!empty($corePromptText)) {
            // 2. Siapkan data pengganti (bisa diambil dari Auth / Model Subject)
            $namaUser  = Auth::user()->name ?? 'Siswa';
            $namaMapel = $subjectName ?? 'Mata Pelajaran';

            // 3. Lakukan replacement variabel {{...}}
            $replacements = [
                '{{NAMA_USER}}'  => $namaUser,
                '{{NAMA_MAPEL}}' => $namaMapel,
            ];

            $corePromptText = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $corePromptText
            );

            // 4. Gabungkan ke system prompt
            $systemPrompt = !empty($systemPrompt)
                ? $corePromptText . "\n\n" . $systemPrompt
                : $corePromptText;
        }
        // ----------------------------------------

        foreach ($this->fallbackModels as $modelName) {
            try {
                $model = Gemini::generativeModel($modelName);

                if (!empty($systemPrompt)) {
                    $model = $model->withSystemInstruction(
                        Content::parse(part: $systemPrompt)
                    );
                }

                $formattedHistory = [];
                foreach ($chatHistory as $chat) {
                    $role = ($chat['role'] === 'user') ? Role::USER : Role::MODEL;

                    if (!empty($chat['text'])) {
                        $formattedHistory[] = Content::parse(
                            part: $chat['text'],
                            role: $role
                        );
                    }
                }

                $currentParts = [];

                if (!empty($userMessage)) {
                    $currentParts[] = $userMessage;
                }

                // 1. Loop file referensi bawaan dari Subject (tersimpan di Storage Public)
                if (!empty($subjectFiles)) {
                    foreach ($subjectFiles as $sFile) {
                        if (isset($sFile['path']) && Storage::disk('public')->exists($sFile['path'])) {
                            $fullPath = Storage::disk('public')->path($sFile['path']);
                            $mimeRaw = mime_content_type($fullPath);
                            $mimeType = MimeType::tryFrom($mimeRaw) ?? MimeType::IMAGE_JPEG;

                            $currentParts[] = new Blob(
                                mimeType: $mimeType,
                                data: base64_encode(file_get_contents($fullPath))
                            );
                        }
                    }
                }

                // 2. Loop file yang dikirim langsung oleh siswa dari input chat
                if (!empty($files)) {
                    foreach ($files as $file) {
                        if ($file instanceof UploadedFile) {
                            $mimeType = MimeType::tryFrom($file->getMimeType()) ?? MimeType::IMAGE_JPEG;
                            $currentParts[] = new Blob(
                                mimeType: $mimeType,
                                data: base64_encode(file_get_contents($file->getRealPath()))
                            );
                        }
                    }
                }

                if (!empty($formattedHistory)) {
                    $chatSession = $model->startChat(history: $formattedHistory);
                    $response = $chatSession->sendMessage($currentParts);
                } else {
                    $response = $model->generateContent(...$currentParts);
                }

                return $response->text();
            } catch (\Throwable $e) {
                Log::warning("Gemini API Error pada model [{$modelName}]: " . $e->getMessage());
                continue;
            }
        }

        return 'Maaf, layanan AI saat ini sedang padat. Silakan coba beberapa saat lagi.';
    }
}
