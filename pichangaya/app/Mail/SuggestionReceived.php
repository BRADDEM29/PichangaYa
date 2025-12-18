<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuggestionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $suggestion;

    public function __construct($suggestion)
    {
        $this->suggestion = $suggestion;
    }

    public function build()
    {
        return $this->subject('⚽ Nueva Sugerencia en PichangaYa')
                    ->view('emails.suggestion-received');
    }
}