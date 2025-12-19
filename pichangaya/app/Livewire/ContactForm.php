<?php
// UBICACIÓN: C:\laragon\www\PichangaYa\pichangaya\app\Livewire\ContactForm.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NuevaConsultaNotification; 

class ContactForm extends Component
{
    public $name, $email, $phone, $subject, $message, $successMessage;
    public $canSend = true; 

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('register');
        }

        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        // 🟢 Capturamos el teléfono del perfil del usuario (si lo tiene)
        $this->phone = $user->phone ?? ''; 
        
        $this->checkCooldown();
    }

    public function checkCooldown()
    {
        $lastContact = Contact::where('email', Auth::user()->email)
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        $this->canSend = !$lastContact;
    }

    public function submit()
    {
        $this->checkCooldown();

        if (!$this->canSend) { return; }

        // 🟢 VALIDACIÓN DE SEGURIDAD (BACKEND)
        // Aquí aplicamos el límite estricto de 1500 caracteres (aprox 200-250 palabras)
        // para asegurar que coincida con el límite visual del frontend.
        $this->validate([
            'subject' => 'required|string|max:100',
            'phone'   => 'required|string|max:20', 
            'message' => 'required|min:10|max:1500', // 🔒 Límite de ~200 palabras
        ], [
            'message.max'    => 'Has excedido el límite de 200 palabras (1500 caracteres). Por favor resume tu mensaje.',
            'phone.required' => 'El número de celular es necesario para contactarte.',
        ]);

        try {
            // Guardar en Base de Datos
            $contact = Contact::create([
                'name'    => $this->name,
                'email'   => $this->email,
                'phone'   => $this->phone,
                'subject' => $this->subject,
                'message' => $this->message,
                'status'  => 'pendiente',
            ]);

            // 🟢 Notificar a Admins
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NuevaConsultaNotification($contact));
            }

            $this->successMessage = '¡Consulta enviada! Nos pondremos en contacto contigo.';
            $this->reset(['subject', 'message']);
            $this->canSend = false;

        } catch (\Exception $e) {
            $this->successMessage = 'Error al enviar. Intenta nuevamente.';
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}