<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'collaborateur', 'lecteur'])
                  ->default('collaborateur')
                  ->after('email');
            $table->enum('status', ['actif', 'en_attente', 'bloque'])
                  ->default('en_attente')
                  ->after('role');
            $table->string('avatar')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'avatar']);
        });
    }
};