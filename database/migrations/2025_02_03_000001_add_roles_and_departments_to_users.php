<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('viewer')->after('email');
            // Roles: admin, ti, servicios_generales, marketing, viewer
            $table->string('department')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('department');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('phone')->nullable()->after('last_login_at');
            $table->string('position')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'department', 'is_active', 'last_login_at', 'phone', 'position']);
        });
    }
};
