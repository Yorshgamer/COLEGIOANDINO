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
        Schema::create('boletines', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20); // Ej: '018' o 'N° 018'
            $table->string('titulo'); // Ej: 'AMAR TAMBIÉN ES PONER LÍMITES'
            $table->text('descripcion')->nullable();
            $table->date('fecha_publicacion');
            $table->string('archivo_pdf'); // Ruta del archivo (ej. 'Boletines/boletin_semana_18.pdf')
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletines');
    }
};
