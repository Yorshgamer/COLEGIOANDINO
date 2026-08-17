<?php

namespace App\Notifications;

use App\Models\Boleta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BoletaDisponibleNotification extends Notification
{
    use Queueable;

    protected $boleta;

    /**
     * Crear nueva instancia de la notificación.
     */
    public function __construct(Boleta $boleta)
    {
        $this->boleta = $boleta;
    }

    /**
     * Canales de entrega de la notificación.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Representación del correo de la notificación.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $periodo   = $this->boleta->mes . ' ' . $this->boleta->anio;
        $nombre    = $notifiable->name;
        $loginUrl  = config('app.url') . '/login';
        $tipoDoc = $this->boleta->type;

        $articuloDocumento = match ($tipoDoc) {
            'CTS' => 'su <strong>Compensación por Tiempo de Servicios (CTS)</strong>',
            'Gratificación' => 'su <strong>Gratificación de Fiestas Patrias / Navidad</strong>',
            'Otro' => 'un <strong>documento laboral</strong>',
            default => 'su <strong>boleta de pago mensual</strong>', // Para 'Boleta Mensual' o cualquier otro
        };

        // Asunto dinámico según el tipo de documento
        $asuntoEmail = match ($tipoDoc) {
            'CTS', 'Gratificación' => "📄 Liquidación de {$tipoDoc} Disponible — Período {$periodo}",
            default => "📄 Boleta de Pago Disponible — Período {$periodo}",
        };

        return (new MailMessage)
            ->subject($asuntoEmail)
            ->greeting('Colegio Particular Andino')

            // 🎯 Usamos HTML nativo puro para las negritas
            ->line('Estimado(a) colaborador(a) <strong>' . $nombre . '</strong>,')
            ->line(
                'Le comunicamos que ' . $articuloDocumento . ' correspondiente al período ' .
                '<strong>' . $periodo . '</strong> ya se encuentra disponible en la plataforma de la Intranet institucional.'
            )
            ->action('Ingresar a la Intranet', $loginUrl)
            ->line('Por favor, ingrese al sistema para revisar los conceptos y proceder con el visado digital obligatorio.')
            ->line('Si tiene alguna duda o inconveniente con el acceso, comuníquese inmediatamente con el Área de Informática.')

            // 🎯 Despedida formateada con HTML limpio sin asteriscos sueltos
            ->salutation('Área de Informática & Recursos Humanos<br><strong>Intranet Colegio Andino</strong>');
    }

    /**
     * Representación en array de la notificación.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'boleta_id' => $this->boleta->id,
            'periodo'   => $this->boleta->mes . ' ' . $this->boleta->anio,
        ];
    }
}
