<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // 1. Quién envía el mensaje (Obligatorio)
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            
            // 2. Contextos de Chat (Todos nulleables porque el mensaje solo pertenece a uno a la vez)
            
            // A. Chat Privado (Usuario a Usuario)
            $table->foreignId('receiver_id')->nullable()->constrained('users')->cascadeOnDelete();
            
            // B. Chat de Sala de Espera (Lobby)
            $table->foreignId('lobby_id')->nullable()->constrained()->cascadeOnDelete();
            
            // C. Chat de Grupo de Amigos (Party)
            $table->foreignId('party_id')->nullable()->constrained()->cascadeOnDelete();
            
            // 3. Filtro de Equipo (La clave para tu requerimiento)
            // Si es 'A' -> Solo lo lee el Equipo A
            // Si es 'B' -> Solo lo lee el Equipo B
            // Si es NULL -> Es Chat General (Todos)
            $table->string('team_side', 1)->nullable(); 
            
            // 4. Contenido y Metadatos
            $table->text('content');
            $table->string('type')->default('text'); // text, system, invite
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};