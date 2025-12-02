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
    Schema::table('canchas', function (Blueprint $table) {
        $table->time('open_time')->default('08:00:00')->after('description'); // Hora apertura
        $table->time('close_time')->default('23:00:00')->after('open_time');  // Hora cierre
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            //
        });
    }
};
