<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('canchas', function (Blueprint $table) {
        // Opción A: Eliminar la columna (Recomendado si ya migraste los datos)
        // Primero eliminamos la llave foránea si existe
        $table->dropForeign(['sport_id']); 
        $table->dropColumn('sport_id');
    });
}

public function down(): void
{
    Schema::table('canchas', function (Blueprint $table) {
        $table->foreignId('sport_id')->nullable()->constrained();
    });
}
};
