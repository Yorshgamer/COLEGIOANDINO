<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mailer\Transport\AbstractTransport;

class MicrosoftGraphMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 🚀 Extendemos el MailManager de Laravel en caliente
        Mail::extend('microsoft-graph', function (array $config) {
            return new class($config) extends AbstractTransport {
                protected array $config;

                public function __construct(array $config) {
                    parent::__construct();
                    $this->config = $config;
                }

                protected function doSend(SentMessage $message): void
                {
                    $email = MessageConverter::toEmail($message->getOriginalMessage());
                    
                    // 1. 🔐 Solicitar Token de Acceso (OAuth 2.0 Client Credentials)
                    $tokenResponse = Http::withoutVerifying()->asForm()->post("https://login.microsoftonline.com/{$this->config['tenant_id']}/oauth2/v2.0/token", [
                        'grant_type'    => 'client_credentials',
                        'client_id'     => $this->config['client_id'],
                        'client_secret' => $this->config['client_secret'],
                        'scope'         => 'https://graph.microsoft.com/.default',
                    ]);

                    if (!$tokenResponse->successful()) {
                        throw new \Exception('Error de autenticación con Azure Active Directory: ' . $tokenResponse->body());
                    }

                    $accessToken = $tokenResponse->json()['access_token'];

                    // 2. 📄 Mapear destinatarios
                    $toRecipients = [];
                    foreach ($email->getTo() as $address) {
                        $toRecipients[] = ['emailAddress' => ['address' => $address->getAddress()]];
                    }

                    // 3. ✈️ Construir y enviar el payload a Microsoft Graph API
                    $fromAddress = $email->getFrom()[0]->getAddress();
                    
                    $graphResponse = Http::withoutVerifying()->withToken($accessToken)
                        ->post("https://graph.microsoft.com/v1.0/users/{$fromAddress}/sendMail", [
                            'message' => [
                                'subject' => $email->getSubject(),
                                'body' => [
                                    'contentType' => $email->getHtmlBody() ? 'HTML' : 'Text',
                                    'content' => $email->getHtmlBody() ?: $email->getTextBody(),
                                ],
                                'toRecipients' => $toRecipients,
                            ],
                            'saveToSentItems' => 'true',
                        ]);

                    if (!$graphResponse->successful()) {
                        throw new \Exception('Error al despachar el correo mediante Microsoft Graph: ' . $graphResponse->body());
                    }
                }

                public function __toString(): string
                {
                    return 'microsoft-graph';
                }
            };
        });
    }
}
