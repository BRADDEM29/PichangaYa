<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Construimos la URL de reseteo (la que sale en el botón)
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Recuperación de Contraseña') // 1. Asunto del correo
            ->greeting('¡Hola!') // 2. Saludo inicial
            ->line('Estás recibiendo este correo porque recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Restablecer Contraseña', $url) // 3. Texto del botón
            ->line('Este enlace caducará en 60 minutos.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')])
            ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.')
            ->salutation('Saludos, ' . config('app.name')); // 4. Despedida (toma el nombre de tu .env)
    }
}