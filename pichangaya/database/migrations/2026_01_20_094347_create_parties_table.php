<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Crear tabla parties si no existe
        if (!Schema::hasTable('parties')) {
            Schema::create('parties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('leader_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->string('invite_code')->unique();
                $table->timestamps();
            });
        }

        // 2️⃣ Agregar party_id a users si no existe
        if (!Schema::hasColumn('users', 'party_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('party_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('parties')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // 1️⃣ Quitar FK y columna party_id de users
        if (Schema::hasColumn('users', 'party_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['party_id']);
                $table->dropColumn('party_id');
            });
        }

        // 2️⃣ Eliminar tabla parties
        if (Schema::hasTable('parties')) {
            Schema::dropIfExists('parties');
        }
    }
};
