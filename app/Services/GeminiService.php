<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Blob;
use Gemini\Data\Content;
use Gemini\Enums\MimeType;
use Gemini\Enums\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Daftar model Gemini (Free Tier / Hemat) yang akan dicoba secara berurutan.
     * Jika model pertama limit/error, otomatis beralih ke model berikutnya.
     */
    protected array $fallbackModels = [
        // --- Lini Utama Gemini 3.x & 3.5 ---
        'gemini-3.5-flash',
        'gemini-3.5-flash-lite',
        'gemini-3.1-flash-lite',

        // --- Lini Legacy Gemini 2.5 ---
        'gemini-2.5-flash',
        'gemini-2.5-flash-lite',
        'gemini-2.5-pro',

        // --- Lini Klasik Gemini 1.5 ---
        'gemini-1.5-flash',
        'gemini-1.5-pro',
    ];

    /**
     * Kirim prompt ke Gemini API dengan System Prompt, Chat History, dan Banyak File/Gambar
     */
    public function ask(
        ?string $systemPrompt = null,
        ?string $userMessage = null,
        array $chatHistory = [],
        array $files = [] // Diubah menjadi Array untuk mendukung banyak file
    ): string {
        if (empty($userMessage) && empty($files)) {
            return 'Pesan atau gambar tidak boleh kosong.';
        }

        // Loop mencoba setiap model jika terjadi error/limit pada model sebelumnya
        foreach ($this->fallbackModels as $modelName) {
            try {
                // 1. Inisialisasi Model saat ini
                $model = Gemini::generativeModel($modelName);

                // 2. Pasang System Instruction jika ada
                if (!empty($systemPrompt)) {
                    $model = $model->withSystemInstruction(
                        Content::parse(part: $systemPrompt)
                    );
                }

                // 3. Format Chat History sesuai SDK
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

                // 4. Susun Konten Pesan Terbaru & Banyak Gambar/File
                $currentParts = [];

                if (!empty($userMessage)) {
                    $currentParts[] = $userMessage;
                }

                // Loop setiap file yang dikirimkan
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

                // 5. Eksekusi Request ke Gemini API
                if (!empty($formattedHistory)) {
                    $chatSession = $model->startChat(history: $formattedHistory);
                    $response = $chatSession->sendMessage($currentParts);
                } else {
                    $response = $model->generateContent(...$currentParts);
                }

                // Berhasil mendapatkan balasan, langsung kembalikan teksnya
                return $response->text();
            } catch (\Throwable $e) {
                // Catat detail error ke file log
                Log::warning("Gemini API Error pada model [{$modelName}]: " . $e->getMessage());

                // Lanjut ke model berikutnya di siklus loop
                continue;
            }
        }

        // 6. Jika SEMUA model gagal/limit
        return 'Maaf, layanan AI saat ini sedang padat. Silakan coba beberapa saat lagi.';
    }
}
