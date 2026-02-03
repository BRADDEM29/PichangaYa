<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('lobbies', function (Blueprint $table) {
        // Cambiamos la columna a string para evitar errores de ENUM
        $table->string('status')->change();
    });
}
   
};
