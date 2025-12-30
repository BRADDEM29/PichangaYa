<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
    Schema::create('favorites', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('cancha_id')->constrained()->onDelete('cascade');
        $table->unique(['user_id', 'cancha_id']); // Evita duplicados
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
