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
        // Columna booleana, por defecto NO es destacada
        $table->boolean('is_featured')->default(false)->after('description');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            //
        });
    }
};
