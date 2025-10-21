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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('restrict');
            $table->foreignId('employee_id')->constrained()->onDelete('restrict');
            $table->date('assigned_date'); // Fecha de entrega
            $table->date('returned_date')->nullable(); // Fecha de devolución
            $table->text('assignment_observations')->nullable(); // Observaciones al entregar
            $table->text('return_observations')->nullable(); // Observaciones al devolver
            $table->enum('condition_on_assignment', ['new', 'good', 'fair', 'poor'])->default('good');
            $table->enum('condition_on_return', ['good', 'fair', 'poor', 'damaged'])->nullable();
            $table->boolean('is_active')->default(true); // Si está actualmente asignado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
