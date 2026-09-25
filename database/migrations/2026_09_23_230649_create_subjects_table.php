<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: Matematika, Bahasa Inggris
            $table->string('icon')->default('📚'); // emoji atau icon class
            $table->text('system_prompt'); // Prompt khusus AI untuk mapel ini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
