<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

// 🎯 UNIFICADO: Declaramos el comando y el parámetro obligatorio mediante el Atributo de PHP 8
#[Signature('mail:test-graph {correo}')]
#[Description('Prueba el envío de correos institucionales a través de la API de Microsoft Graph')]
class TestMicrosoftGraphMail extends Command
{
    public function handle()
    {
        $destinatario = $this->argument('correo');

        $this->info("Iniciando prueba de envío vía API REST hacia: {$destinatario}...");

        try {
            Mail::raw('¡Hola! Esta es una prueba de comunicación exitosa entre la Intranet del Colegio Andino y la API de Microsoft Graph usando OAuth 2.0. Si lees esto, la infraestructura está 100% operativa.', function ($message) use ($destinatario) {
                $message->to($destinatario)
                        ->subject('🚀 Prueba de Conectividad — API Mailer');
            });

            // Nota: En Laravel el método correcto para sugerencias o texto de éxito en consola es $this->line() o $this->info()
            $this->info("¡Éxito rotundo! El correo ha sido enviado y aceptado por los servidores de Microsoft sin errores.");
        } catch (\Exception $e) {
            $this->error("Error en la conexión perimetral de Azure:");
            $this->line($e->getMessage());
        }
    }
}