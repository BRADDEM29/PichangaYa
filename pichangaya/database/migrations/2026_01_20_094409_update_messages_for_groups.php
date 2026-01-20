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
        Schema::table('messages', function (Blueprint $table) {
            // Hacemos el receptor opcional
            $table->foreignId('receiver_id')->nullable()->change();

            // Agregamos contextos
            $table->foreignId('party_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('lobby_id')->nullable()->constrained()->cascadeOnDelete();

            // Tipo de mensaje
            $table->string('type')->default('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Revertir cambios es complicado si ya hay datos nulos, 
            // pero eliminamos las columnas nuevas.
            $table->dropForeign(['party_id']);
            $table->dropColumn('party_id');
            
            $table->dropForeign(['lobby_id']);
            $table->dropColumn('lobby_id');
            
            $table->dropColumn('type');
        });
    }
};