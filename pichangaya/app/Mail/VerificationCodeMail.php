<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code; // Aquí recibimos el código

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Código de Verificación - PichangaYa',
        );
    }

    public function content(): Content
    {
        // Apunta a la vista que crearemos en el paso 3
        return new Content(
            markdown: 'emails.verification-code',
        );
    }
}