<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Notifications\NuevaConsultaNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Contact;

class NuevaConsultaNotification extends Notification
{
    use Queueable;

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via($notifiable)
    {
        return ['database']; // ✅ Importante: Canal base de datos
    }

    // ✅ Usamos toDatabase para asegurar que se guarde correctamente
    public function toDatabase($notifiable)
    {
        return [
            'titulo'  => '📩 Nueva Consulta',
            'mensaje' => 'De: ' . $this->contact->name . ' (' . $this->contact->subject . ')',
            'icono'   => 'mail', // Esto activa el sobre en tu menú
            'url'     => route('admin.contacts.index'),
            'id'      => $this->contact->id,
        ];
    }
}