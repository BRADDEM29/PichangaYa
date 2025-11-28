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
        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('address'); 
            $table->decimal('price_per_hour', 8, 2); 
            $table->text('description')->nullable(); 
            
            // Relaciones (Foreign Keys)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Dueño
            
            // Relación con Deportes (Tabla 'sports')
            $table->foreignId('sport_id')->constrained('sports'); 
            
            // Relación con Distritos (Tabla 'districts')
            $table->foreignId('district_id')->constrained('districts'); 

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canchas');
    }
};