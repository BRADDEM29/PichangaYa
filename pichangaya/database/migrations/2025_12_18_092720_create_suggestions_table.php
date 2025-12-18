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
    Schema::create('suggestions', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable(); // Opcional, por si quieren ser anónimos
        $table->integer('rating'); // 1 a 5 (Emoji)
        $table->text('comment');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
