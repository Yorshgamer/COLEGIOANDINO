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
        $loginUrl = config('app.url', 'http://localhost:8000') . '/login';

        return (new MailMessage)
            ->subject('🔐 Sus Credenciales de Acceso — Intranet Andino')
            ->greeting('COLEGIO PARTICULAR ANDINO')
            ->line('Estimado(a) colaborador(a) <strong>' . $notifiable->name . '</strong>,')
            ->line('Le comunicamos que se han actualizado o generado sus credenciales de acceso para la plataforma de la Intranet.')
            ->line('A continuación, se detallan sus credenciales provisionales de seguridad:')
            
            // 🎯 Usamos HTML nativo para asegurar la compatibilidad de negritas
            ->line('• <strong>Usuario (Correo):</strong> ' . $notifiable->email)
            ->line('• <strong>Contraseña Temporal:</strong> <code style="background-color: #f4f4f5; padding: 2px 6px; border-radius: 4px; font-family: monospace; color: #af0c27;">' . $this->passwordTemporal . '</code>')
            
            ->action('Iniciar Sesión en Intranet', $loginUrl)
            ->line('Por motivos de seguridad y digitalización del plantel, se le recomienda cambiar esta contraseña una vez ingrese a su panel de configuración de perfil.')
            ->salutation('Área de Informática & Soporte Técnico<br><strong>Intranet Colegio Andino</strong>');
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
