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
    Schema::create('contacts', function (Table $table) {
        $table->id();
        $table->string('name');
        $table->string('email');
        $table->string('subject'); // Ejemplo: "Problema con reserva", "Duda sobre pago"
        $table->text('message');
        $table->boolean('is_resolved')->default(false); // Para que marques cuando ya atendiste al cliente
        $table->timestamps();
    });
}
};
