<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregamos columna de Strikes (si no existe)
            if (!Schema::hasColumn('users', 'consecutive_cancellations')) {
                $table->integer('consecutive_cancellations')->default(0)->after('profile_photo_path');
            }

            // Agregamos columna de Bloqueo (si no existe)
            if (!Schema::hasColumn('users', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('consecutive_cancellations');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['consecutive_cancellations', 'is_blocked']);
        });
    }
};