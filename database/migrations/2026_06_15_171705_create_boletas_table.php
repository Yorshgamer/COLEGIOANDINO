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
        Schema::create('boletas', function (Blueprint $table) {
            $table->id();
            // Relación atómica con el usuario/empleado
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Clasificación del Documento Laboral Peruano
            $table->enum('type', ['Boleta Mensual', 'Gratificación', 'CTS', 'Otro'])->default('Boleta Mensual');
            
            // Periodo Cronológico
            $table->string('mes', 20); // Ej: 'Enero', 'Mayo', 'Noviembre'
            $table->integer('anio');   // Ej: 2026

            // Almacenamiento del Archivo original subido por RRHH
            $table->string('pdf_path'); 

            // Flujo de control de estados mejorado
            $table->enum('status', ['Procesada', 'Vista', 'Firmada', 'Observada'])->default('Procesada');
            
            // Auditoría de firmas e incidencias
            $table->timestamp('signed_at')->nullable(); // Fecha y hora exacta de la firma de conformidad
            $table->text('observation_message')->nullable(); // Mensaje detallado si el profesor observa la boleta
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletas');
    }
};
