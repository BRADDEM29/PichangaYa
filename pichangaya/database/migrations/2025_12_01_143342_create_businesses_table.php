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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->foreignId('district_id')->constrained();
            
            // --- AGREGAR ESTA LÍNEA ---
            // Por ahora asumimos que el negocio tiene un deporte principal
            $table->foreignId('sport_id')->constrained(); 
            // --------------------------

            $table->foreignId('user_id')->constrained();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
