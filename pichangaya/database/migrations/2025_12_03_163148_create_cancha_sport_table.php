<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cancha_sport', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea hacia la tabla 'canchas'
            $table->foreignId('cancha_id')->constrained()->onDelete('cascade');
            
            // Llave foránea hacia la tabla 'sports'
            $table->foreignId('sport_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }
};
