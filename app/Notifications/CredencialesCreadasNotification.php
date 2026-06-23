<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CredencialesCreadasNotification extends Notification
{
    use Queueable;

    protected $passwordTemporal;

    /**
     * Create a new notification instance.
     */
    public function __construct($passwordTemporal)
    {
$this->passwordTemporal = $passwordTemporal;
    }

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
            ->subject('🔐 Sus Credenciales de Acceso a la Intranet')
            ->greeting('COLEGIO PARTICULAR ANDINO')
            ->line('Estimado(a) ' . $notifiable->name . ',')
            ->line('Se ha creado con éxito su cuenta de usuario para acceder a la plataforma institucional.')
            ->line('A continuación, se detallan sus credenciales de acceso provisionales:')
            ->line('**Correo Electrónico:** ' . $notifiable->email)
            ->line('**Contraseña Temporal:** `' . $this->passwordTemporal . '`')
            ->action('Iniciar Sesión Aquí', url('http://localhost:8000/login'))
            ->line('Por motivos de higiene digital y seguridad, el sistema le solicitará cambiar esta contraseña obligatoriamente en su primer ingreso.')
            ->salutation('INTRANET — Área de Informática');
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
