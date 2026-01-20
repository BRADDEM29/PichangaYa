<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabla de Torneos (Le agregué prize_description que usas en el controlador)
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('prize_description')->nullable(); // Agregado para evitar errores
            $table->string('slug')->nullable(); // Agregado para las rutas amigables
            $table->enum('status', ['open', 'active', 'finished'])->default('open');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('cancha_id')->nullable()->constrained('canchas'); // Relación con Cancha
            $table->dateTime('start_date')->nullable();
            $table->timestamps();
        });

        // 2. Tabla de Equipos
        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->string('team_name');
            $table->string('logo_path')->nullable(); 
            $table->timestamps();
        });

        // 3. Tabla de Partidos (Bracket) - ¡AQUÍ ESTÁ EL CAMBIO IMPORTANTE!
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            
            // --- NUEVO: Columna ROUND (Vital para el orden visual) ---
            $table->integer('round')->default(1); 
            
            // CAMBIO: Cambiamos 'enum' por 'string' para permitir cualquier fase
            $table->string('phase')->nullable(); 
            
            $table->integer('match_number'); 
            
            $table->foreignId('team1_id')->nullable()->constrained('tournament_teams');
            $table->foreignId('team2_id')->nullable()->constrained('tournament_teams');
            
            $table->integer('score1')->default(0); // Default 0 ayuda a evitar nulos
            $table->integer('score2')->default(0);
            
            $table->foreignId('winner_id')->nullable()->constrained('tournament_teams');
            
            // Relación recursiva para saber a qué partido va el ganador
            $table->foreignId('next_match_id')->nullable()->constrained('matches')->nullOnDelete(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matches');
        Schema::dropIfExists('tournament_teams');
        Schema::dropIfExists('tournaments');
    }
};