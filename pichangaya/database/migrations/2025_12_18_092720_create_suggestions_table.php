<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            // 🟢 MEJORA: Relacionamos con el usuario
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable(); 
            $table->integer('rating'); 
            $table->text('comment');
            // 🟢 MEJORA: Estado de la sugerencia
            $table->enum('status', ['pendiente', 'leido', 'implementado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};