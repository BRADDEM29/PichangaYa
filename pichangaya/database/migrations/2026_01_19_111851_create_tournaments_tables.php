<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla de Torneos
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['open', 'active', 'finished'])->default('open');
            $table->foreignId('created_by')->constrained('users'); // Admin o Dueño
            $table->timestamps();
        });

        // Tabla de Equipos en el Torneo
        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->string('team_name'); // Nombre del equipo (Ej: "Los Tigres")
            // Podrías relacionarlo con 'parties' si quisieras, pero para simplificar usamos nombres
            $table->string('logo_path')->nullable(); 
            $table->timestamps();
        });

        // Tabla de Partidos (Bracket)
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->enum('phase', ['quarter_final', 'semi_final', 'final']);
            $table->integer('match_number'); // 1 a 4 para cuartos, 1-2 semis, 1 final
            
            $table->foreignId('team1_id')->nullable()->constrained('tournament_teams');
            $table->foreignId('team2_id')->nullable()->constrained('tournament_teams');
            
            $table->integer('score1')->nullable();
            $table->integer('score2')->nullable();
            
            $table->foreignId('winner_id')->nullable()->constrained('tournament_teams');
            $table->foreignId('next_match_id')->nullable()->constrained('matches'); // Para avanzar automáticamente
            
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