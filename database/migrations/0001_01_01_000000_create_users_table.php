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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Nombres y Apellidos separados (Estándar RENIEC)
            $table->string('first_name');      // Nombres
            $table->string('paternal_surname'); // Apellido Paterno
            $table->string('maternal_surname'); // Apellido Materno

            // Datos de Identificación y Contacto
            $table->string('dni', 8)->unique(); // DNI exacto de 8 dígitos para Perú
            $table->string('phone', 9)->nullable();

            // Autenticación y Credenciales
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Control Operativo de Estado
            $table->enum('status', ['Activo', 'Inactivo'])->default('Activo');

            // Almacenamiento de la firma digital (Ruta de la imagen PNG de la firma)
            $table->string('signature_path')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Soft Deletes: Crea la columna 'deleted_at' para despidos/retiros sin perder datos
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
