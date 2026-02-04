<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Campos para parlantes
            $table->integer('power_watts')->nullable()->after('has_microphone');
            $table->string('frequency_response')->nullable()->after('power_watts');
            $table->boolean('has_bluetooth')->default(false)->after('frequency_response');
            $table->string('battery_life')->nullable()->after('has_bluetooth'); // Para parlantes portátiles
            
            // Campo general para todos los activos
            $table->string('warranty_until')->nullable()->after('battery_life');
            $table->enum('condition', ['nuevo', 'bueno', 'regular', 'malo', 'reparacion'])->default('bueno')->after('warranty_until');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'power_watts',
                'frequency_response',
                'has_bluetooth',
                'battery_life',
                'warranty_until',
                'condition'
            ]);
        });
    }
};
