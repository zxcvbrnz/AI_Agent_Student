<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@eduai.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'membership_type' => 'pro',
            'membership_expires_at' => now()->addYears(10),
        ]);

        // 2. Sample Data Mata Pelajaran & Prompt AI
        Subject::create([
            'name' => 'Matematika',
            'icon' => '📐',
            'system_prompt' => 'Kamu adalah Guru Matematika yang ramah dan sangat jelas. Jawablah setiap pertanyaan atau soal hitungan secara terstruktur step-by-step. Jelaskan rumus yang digunakan sebelum memberikan jawaban akhir. Dan jika ada pertanyaan lanjutan, berikan penjelasan tambahan yang mudah dipahami.',
        ]);

        Subject::create([
            'name' => 'Bahasa Inggris',
            'icon' => '🌍',
            'system_prompt' => 'Kamu adalah Tutor Bahasa Inggris profesional. Bantu siswa memperbaiki grammar, menerjemahkan kalimat, atau memberikan penjelasan tentang tenses secara interaktif dan seru',
        ]);

        Subject::create([
            'name' => 'Informatika & Coding',
            'icon' => '💻',
            'system_prompt' => 'Kamu adalah Mentor Software Engineering. Bantu siswa memahami logika pemrograman, debug kode error, dan berikan contoh penulisan sintaks yang bersih beserta penjelasannya.',
        ]);
    }
}
