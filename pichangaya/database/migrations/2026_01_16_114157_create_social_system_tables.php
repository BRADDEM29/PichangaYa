<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Limpieza preventiva (Si existen las tablas viejas, las borramos)
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('friendships');
        Schema::dropIfExists('parties');
        Schema::enableForeignKeyConstraints();

        // 2. Crear Tabla de Amistades
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('friend_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'blocked'])->default('pending');
            $table->timestamps();
        });

        // 3. Crear Tabla de Parties (Grupos)
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leader_id')->constrained('users');
            $table->string('invite_code')->unique();
            $table->timestamps();
        });

        // 4. Modificar tabla users (Con verificación para no repetir columnas)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'party_id')) {
                $table->foreignId('party_id')->nullable()->constrained('parties')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['online', 'offline', 'ingame', 'searching'])->default('online');
            }
        });
    }

    public function down()
    {
        // Revertir cambios
        Schema::dropIfExists('friendships');
        
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'party_id')) {
                $table->dropForeign(['party_id']);
                $table->dropColumn('party_id');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
        
        Schema::dropIfExists('parties');
    }
};