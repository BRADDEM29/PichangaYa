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
    Schema::table('canchas', function (Blueprint $table) {
        // Coordenadas con precisión suficiente para mapas (Latitud y Longitud)
        // Nullable para no romper registros existentes del Sprint 6
        $table->decimal('lat', 10, 8)->nullable()->after('address');
        $table->decimal('lng', 11, 8)->nullable()->after('lat');
    });
}

public function down(): void
{
    Schema::table('canchas', function (Blueprint $table) {
        $table->dropColumn(['lat', 'lng']);
    });
}
};
