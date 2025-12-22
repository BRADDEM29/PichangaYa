<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaConductaUsuario extends Notification
{
    use Queueable;

    public $user;
    public $strikes;

    public function __construct($user, $strikes)
    {
        $this->user = $user;
        $this->strikes = $strikes;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo'  => '⚠️ Alerta de Conducta',
            'mensaje' => "El usuario {$this->user->name} ha acumulado {$this->strikes} cancelaciones consecutivas.",
            'icono'   => 'warning', 
            'url'     => route('admin.users.index'), // Llevar al admin a la gestión de usuarios
            'id'      => $this->user->id,
        ];
    }
}