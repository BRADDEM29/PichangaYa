<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NuevaSugerenciaNotification extends Notification
{
    use Queueable;

    public $suggestion;

    public function __construct($suggestion)
    {
        $this->suggestion = $suggestion;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo'  => '💡 Nueva Sugerencia',
            'mensaje' => 'Calificación: ' . $this->suggestion->rating . '/5',
            'icono'   => 'lightbulb', // Esto activa el foco en tu menú
            'url'     => route('admin.suggestions.received'),
            'id'      => $this->suggestion->id,
        ];
    }
}