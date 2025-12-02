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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();

            // Fechas y horas de la reserva
            $table->dateTime('start_time')->comment('Hora y fecha de inicio de la reserva');
            $table->dateTime('end_time')->comment('Hora y fecha de fin de la reserva');

            // Información financiera
            $table->decimal('total_price', 8, 2);

            // Estado de la reserva (ej: pendiente, confirmada, cancelada)
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');

            // Relaciones (Foreign Keys)
            // Relación con el usuario que hace la reserva
            // 'user_id' está en la tabla 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            
            // Relación con la cancha que se reserva
            // 'cancha_id' está en la tabla 'canchas'
            $table->foreignId('cancha_id')->constrained('canchas')->onDelete('cascade'); 

            $table->timestamps();

            // Índice único para la lógica de choques de horarios (Opcional, pero recomendado)
            // Esto asegura que no pueda haber dos reservas para la misma cancha en el mismo momento.
            // La validación en el backend será más completa (ver SCRUM-56), pero este es un buen seguro a nivel de DB.
            $table->index(['cancha_id', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};