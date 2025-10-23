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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('code')->unique(); // Código interno del activo
            $table->string('brand'); // Marca
            $table->string('model');
            $table->string('serial_number')->unique()->nullable();

            // Campos para CELULAR
            $table->string('imei')->unique()->nullable();
            $table->string('imei_2')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('operator_name')->nullable();

            // Campos para PC, LAPTOP
            $table->string('processor')->nullable(); // Procesador
            $table->string('ram')->nullable(); // Memoria RAM (ej: 8GB, 16GB)
            $table->string('storage')->nullable(); // Almacenamiento (ej: 256GB SSD, 1TB HDD)
            $table->string('storage_type')->nullable(); // Tipo (SSD, HDD, NVMe)
            $table->string('graphics_card')->nullable(); // Tarjeta gráfica
            $table->string('operating_system')->nullable(); // Sistema operativo
            $table->string('screen_size')->nullable(); // Tamaño de pantalla (para laptop)

            // Campos para MONITOR
            $table->string('screen_size_monitor')->nullable(); // Tamaño
            $table->string('resolution')->nullable(); // Resolución (1920x1080, etc)
            $table->string('panel_type')->nullable(); // Tipo de panel (IPS, TN, VA)
            $table->string('refresh_rate')->nullable(); // Tasa de refresco (60Hz, 144Hz)

            // Campos para MOUSE, TECLADO, AUDÍFONOS
            $table->string('connection_type')->nullable(); // Conexión (USB, Bluetooth, Inalámbrico)
            $table->boolean('is_wireless')->nullable(); // Si es inalámbrico

            // Campos para AUDÍFONOS
            $table->string('audio_type')->nullable(); // Tipo (Over-ear, In-ear, On-ear)
            $table->boolean('has_microphone')->nullable(); // Si tiene micrófono

            // Campos generales
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->enum('status', ['available', 'assigned', 'maintenance', 'damaged', 'retired'])->default('available');
            $table->text('observations')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
