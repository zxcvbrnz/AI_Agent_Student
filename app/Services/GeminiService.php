<?php

namespace App\Services;

use App\Models\CorePromt;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Blob;
use Gemini\Data\Content;
use Gemini\Enums\MimeType;
use Gemini\Enums\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeminiService
{
    protected array $fallbackModels = [
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
        array $subjectFiles = []
    ): string {
        if (empty($userMessage) && empty($files) && empty($subjectFiles)) {
            return 'Pesan atau gambar tidak boleh kosong.';
        }

        // --- 1. PROSES CORE PROMPT & REPLACEMENT ---
        $corePromptData = CorePromt::first();
        $corePromptText = $corePromptData ? trim($corePromptData->promt) : '';

        $finalSystemInstruction = '';

        if (!empty($corePromptText)) {
            $namaUser  = Auth::user()->name ?? 'Siswa';
            $namaMapel = $subjectName ?? 'Mata Pelajaran';

            $replacements = [
                '{{NAMA_USER}}'  => $namaUser,
                '{{NAMA_MAPEL}}' => $namaMapel,
            ];

            $corePromptText = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $corePromptText
            );

            $finalSystemInstruction = $corePromptText;
        }

        // Gabungkan dengan system prompt tambahan (jika ada) dengan pemisah yang jelas
        if (!empty($systemPrompt)) {
            $finalSystemInstruction = !empty($finalSystemInstruction)
                ? $finalSystemInstruction . "\n\n--- PETUNJUK TAMBAHAN ---\n" . $systemPrompt
                : $systemPrompt;
        }
        dd($finalSystemInstruction);
        // -------------------------------------------

        foreach ($this->fallbackModels as $modelName) {
            try {
                $model = Gemini::generativeModel($modelName);

                // Inject System Instruction secara eksplisit
                if (!empty($finalSystemInstruction)) {
                    $model = $model->withSystemInstruction(
                        Content::parse(part: $finalSystemInstruction)
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

                // 1. Loop file referensi bawaan dari Subject
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

                // 2. Loop file yang dikirim dari input chat
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
                // Log detail error agar bisa dicek di storage/logs/laravel.log
                Log::error("Gemini API Error pada model [{$modelName}]: " . $e->getMessage(), [
                    'exception' => $e
                ]);
                continue;
            }
        }

        return 'Maaf, layanan AI saat ini sedang padat. Silakan coba beberapa saat lagi.';
    }
}
