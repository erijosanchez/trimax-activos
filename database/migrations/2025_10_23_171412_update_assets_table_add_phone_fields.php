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
        Schema::table('assets', function (Blueprint $table) {
            $table->string('imei')->unique()->after('model');
            $table->string('imei_2')->unique()->nullable()->after('imei');
            $table->string('phone')->unique()->after('imei_2');
            $table->string('operator_name')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['imei', 'imei_2', 'phone', 'operator_name']);
        });
    }
};
