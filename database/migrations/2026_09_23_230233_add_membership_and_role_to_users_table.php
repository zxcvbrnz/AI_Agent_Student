<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email'); // admin, user
            $table->string('membership_type')->default('free_trial')->after('role'); // free_trial, pro, expired
            $table->timestamp('membership_expires_at')->nullable()->after('membership_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'membership_type', 'membership_expires_at']);
        });
    }
};