<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fíjate aquí: Schema::table (NO create)
        Schema::table('canchas', function (Blueprint $table) {
            // Agregamos índices para que las búsquedas sean instantáneas
            $table->index('district_id');    // Acelera filtro por distrito
            $table->index('price_per_hour'); // Acelera ordenar por precio
            $table->index('name');           // Acelera buscador de texto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            $table->dropIndex(['district_id']);
            $table->dropIndex(['price_per_hour']);
            $table->dropIndex(['name']);
        });
    }
};