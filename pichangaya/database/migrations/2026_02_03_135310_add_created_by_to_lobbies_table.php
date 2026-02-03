<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('lobbies', function (Blueprint $table) {
        $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade')->after('expires_at');
    });
}

public function down(): void
{
    Schema::table('lobbies', function (Blueprint $table) {
        $table->dropForeign(['created_by']);
        $table->dropColumn('created_by');
    });
}
};
