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
        Schema::table('lobbies', function (Blueprint $table) {
            // Agregamos la columna max_slots (entero), que no sea nula, con valor por defecto 14
            // La colocamos después de la columna 'status' para mantener el orden.
            $table->integer('max_slots')->default(14)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lobbies', function (Blueprint $table) {
            $table->dropColumn('max_slots');
        });
    }
};