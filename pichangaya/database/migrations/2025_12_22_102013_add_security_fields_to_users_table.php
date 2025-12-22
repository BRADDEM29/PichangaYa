<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('consecutive_cancellations')->default(0); // Contador de strikes
            $table->boolean('is_blocked')->default(false); // Estado de bloqueo
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['consecutive_cancellations', 'is_blocked']);
        });
    }
};