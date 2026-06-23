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
     * Create a new notification instance.
     */
    public function __construct(Boleta $boleta)
    {
$this->boleta = $boleta;    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📄 Nueva Boleta de Pago Disponible — ' . $this->boleta->periodo)
            ->greeting('COLEGIO PARTICULAR ANDINO') // Cabecera institucional
            ->line('Estimado(a) ' . $notifiable->name . ',')
            ->line('Le informamos que su boleta de pago correspondiente al periodo operativo **' . $this->boleta->periodo . '** ya se encuentra disponible en nuestro sistema.')
            ->action('Ir al Visor de la Intranet', url('http://localhost:8000/login')) // Botón link para el Login local
            ->line('Por favor, ingrese al enlace para revisar la conformidad del documento y proceder con la firma digital correspondiente.')
            ->salutation('INTRANET — Área de Informática & Recursos Humanos'); // Footer personalizado
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
