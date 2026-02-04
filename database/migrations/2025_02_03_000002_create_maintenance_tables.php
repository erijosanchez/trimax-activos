<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->enum('frequency', ['mensual', 'trimestral', 'semestral', 'anual'])->default('trimestral');
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->foreignId('maintenance_schedule_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('technician_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Usuario que recibe el equipo
            $table->enum('type', ['preventivo', 'correctivo', 'predictivo'])->default('preventivo');
            $table->enum('status', ['programado', 'en_proceso', 'completado', 'cancelado'])->default('programado');
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->text('description');
            $table->text('activities_performed')->nullable();
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();
            $table->json('parts_replaced')->nullable(); // JSON de partes reemplazadas
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('duration_minutes')->nullable(); // Duración del mantenimiento
            $table->string('document_path')->nullable(); // Ruta del acta firmada
            $table->timestamp('technician_signed_at')->nullable();
            $table->timestamp('user_signed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('maintenance_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_id')->constrained()->onDelete('cascade');
            $table->string('item');
            $table->boolean('is_checked')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_checklists');
        Schema::dropIfExists('maintenances');
        Schema::dropIfExists('maintenance_schedules');
    }
};
