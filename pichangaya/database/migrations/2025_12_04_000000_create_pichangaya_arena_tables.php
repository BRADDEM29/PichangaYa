<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABLA DE EQUIPOS (Clubes)
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo_path')->nullable();
            $table->foreignId('captain_id')->constrained('users')->onDelete('cascade'); // Dueño del equipo
            $table->string('team_code')->unique(); // Código para invitar (ej. TEAM-99)
            $table->timestamps();
        });

        // 2. MIEMBROS DEL EQUIPO
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['captain', 'player'])->default('player');
            $table->timestamp('joined_at');
            
            // REGLA: Un usuario solo puede estar en un equipo a la vez
            $table->unique('user_id'); 
        });

        // 3. CAMPEONATOS (Torneos)
        Schema::create('championships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->string('location')->nullable(); // Sede texto o relación
            
            // PREMIO FLEXIBLE (Texto opcional)
            $table->string('prize_description')->nullable(); 
            
            $table->enum('status', ['open', 'active', 'finished'])->default('open');
            $table->foreignId('admin_id')->constrained('users'); // Quién lo creó
            $table->timestamps();
        });

        // 4. INSCRIPCIONES AL TORNEO
        Schema::create('championship_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('championship_id')->constrained('championships')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->enum('status', ['registered', 'eliminated', 'champion'])->default('registered');
            $table->timestamps();
        });

        // 5. AMISTADES (Agenda de Contactos)
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Quien envía
            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade'); // Quien recibe
            $table->enum('status', ['pending', 'accepted', 'blocked'])->default('pending');
            $table->timestamps();
        });

        // 6. LOBBYS (Matchmaking Persistente)
        Schema::create('lobbies', function (Blueprint $table) {
            $table->id();
            // Asumimos que existen las tablas sports y districts (basado en tus archivos anteriores)
            $table->foreignId('sport_id')->constrained('sports')->onDelete('cascade'); 
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            
            $table->enum('status', ['searching', 'confirming', 'active', 'cancelled'])->default('searching');
            $table->dateTime('expires_at'); // El timer de 48h
            $table->timestamps();
        });

        // 7. SLOTS DEL LOBBY (Jugadores dentro)
        Schema::create('lobby_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lobby_id')->constrained('lobbies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->enum('team_side', ['A', 'B'])->nullable(); // Equipo A o B
            $table->boolean('is_captain')->default(false); // Líder de la sala
            $table->dateTime('confirmed_at')->nullable(); // Si es null, no ha aceptado la partida
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lobby_slots');
        Schema::dropIfExists('lobbies');
        Schema::dropIfExists('friendships');
        Schema::dropIfExists('championship_teams');
        Schema::dropIfExists('championships');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');
    }
};