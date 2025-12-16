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
        Schema::table('responsibility_documents', function (Blueprint $table) {
            // Agregar tipo de documento: 'delivery' (entrega) o 'return' (devolución)
            $table->enum('document_type', ['delivery', 'return'])->default('delivery')->after('assignment_id');
            
            // Hacer que document_number ya no sea único globalmente
            // ya que ahora una asignación puede tener 2 documentos (entrega y devolución)
            $table->dropUnique(['document_number']);
        });
        
        // Agregar índice compuesto para evitar duplicados por tipo
        Schema::table('responsibility_documents', function (Blueprint $table) {
            $table->unique(['assignment_id', 'document_type'], 'unique_assignment_document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responsibility_documents', function (Blueprint $table) {
            $table->dropUnique('unique_assignment_document_type');
            $table->dropColumn('document_type');
            $table->unique('document_number');
        });
    }
};
