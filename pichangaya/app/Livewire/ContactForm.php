<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;
use App\Mail\ContactReceived;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ContactForm extends Component
{
    public $name, $email, $subject, $message, $successMessage;
    public $canSend = true; 

    public function mount()
    {
        // 1. Forzar redirección si no hay sesión
        if (!Auth::check()) {
            return redirect()->route('register');
        }

        // 2. Cargar datos del usuario actual
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        
        // 3. Verificar límite inmediatamente
        $this->checkCooldown();
    }

    public function checkCooldown()
    {
        // Buscamos SOLO registros que coincidan exactamente con el email del usuario logueado
        // y que hayan sido creados en las últimas 24 horas.
        $lastContact = Contact::where('email', Auth::user()->email)
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        if ($lastContact) {
            $this->canSend = false;
        } else {
            $this->canSend = true;
        }
    }

    public function submit()
    {
        // Re-verificar antes de procesar
        $this->checkCooldown();

        if (!$this->canSend) {
            return;
        }

        $this->validate([
            'subject' => 'required',
            'message' => 'required|min:10',
        ]);

        try {
            // Guardar la consulta
            $contact = Contact::create([
                'name'    => $this->name,
                'email'   => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]);

            // Enviar el correo a la empresa
            Mail::to('pichangayacusco@gmail.com')->send(new ContactReceived($contact));

            $this->successMessage = '¡Tu consulta ha sido enviada con éxito!';
            
            // Limpiar campos y bloquear envío
            $this->reset(['subject', 'message']);
            $this->canSend = false;

        } catch (\Exception $e) {
            // Si hay un error de conexión de correo, al menos el mensaje se guardó en BD
            $this->successMessage = 'Consulta guardada, pero hubo un detalle al enviar el correo.';
            $this->canSend = false;
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}