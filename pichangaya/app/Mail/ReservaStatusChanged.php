<?php

namespace App\Mail;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }

    public function build()
    {
        // Asunto dinámico según estado
        $estado = strtoupper($this->reserva->status);
        $subject = "Actualización de Reserva #{$this->reserva->id}: {$estado}";

        return $this->subject($subject)
                    ->view('emails.reservas.status-changed');
    }
}