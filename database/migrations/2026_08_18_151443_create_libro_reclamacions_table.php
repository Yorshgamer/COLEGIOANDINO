<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libro_reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('correlativo')->unique(); // REC-2026-0001 / QUE-2026-0001
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('correo');
            $table->string('domicilio');
            $table->string('dni', 20);
            $table->string('telefono', 20);
            $table->string('padre_madre')->nullable();
            
            // Bien contratado
            $table->enum('bien_contratado', ['servicio', 'producto'])->default('servicio');
            $table->text('descripcion_bien');
            
            // Reclamación
            $table->enum('tipo_reclamo', ['reclamo', 'queja'])->default('reclamo');
            $table->text('detalle_reclamo');
            
            // Estado de atención
            $table->enum('estado', ['pendiente', 'en_proceso', 'atendido'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libro_reclamaciones');
    }
};