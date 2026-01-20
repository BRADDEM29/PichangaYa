<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Creamos solo si no existe (seguridad)
        if (!Schema::hasTable('cancha_sport')) {
            Schema::create('cancha_sport', function (Blueprint $table) {
                $table->id();

                // FK hacia canchas
                if (Schema::hasTable('canchas')) {
                    $table->foreignId('cancha_id')
                        ->constrained('canchas')
                        ->cascadeOnDelete();
                } else {
                    $table->unsignedBigInteger('cancha_id');
                }

                // FK hacia sports
                if (Schema::hasTable('sports')) {
                    $table->foreignId('sport_id')
                        ->constrained('sports')
                        ->cascadeOnDelete();
                } else {
                    $table->unsignedBigInteger('sport_id');
                }

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cancha_sport')) {
            Schema::dropIfExists('cancha_sport');
        }
    }
};
