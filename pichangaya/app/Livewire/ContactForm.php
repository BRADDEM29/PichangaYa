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
    // Usamos 'motivo' y 'mensaje' para la vista, pero mapeamos internamente a DB
    public $motivo = ''; 
    public $mensaje = '';
    public $phone = ''; // Necesario para la BD
    
    // Variables internas
    public $enviado = false;
    public $canSend = true;

    // Reglas de validación
    protected $rules = [
        'motivo'  => 'required|min:5|max:100',
        'mensaje' => 'required|min:10|max:1500',
        'phone'   => 'required|string|max:20', // Se valida solo al enviar
    ];

    public function mount()
    {
        // 1. RECUPERAR DATOS SI EL USUARIO ACABA DE LOGUEARSE
        if (session()->has('contact_form_backup')) {
            $backup = session('contact_form_backup');
            
            $this->motivo  = $backup['motivo'];
            $this->mensaje = $backup['mensaje'];

            // Limpiamos la sesión
            session()->forget('contact_form_backup');
        }

        // 2. SI YA ESTÁ LOGUEADO, PRE-LLENAR DATOS
        if (Auth::check()) {
            $user = Auth::user();
            // Si el usuario tiene teléfono en su perfil, lo usamos. Si no, lo dejamos vacío para que lo llene.
            $this->phone = $user->phone ?? ''; 
            
            $this->checkCooldown();
        }
    }

    public function checkCooldown()
    {
        if (Auth::check()) {
            $lastContact = Contact::where('email', Auth::user()->email)
                ->where('created_at', '>=', now()->subHours(24))
                ->first();

            $this->canSend = !$lastContact;
        }
    }

    public function submit()
    {
        // 🟢 A) LÓGICA PARA INVITADOS (GUEST)
        if (!Auth::check()) {
            // Guardamos lo que escribió en la sesión
            session()->put('contact_form_backup', [
                'motivo'  => $this->motivo,
                'mensaje' => $this->mensaje
            ]);

            // Guardamos la intención de volver aquí
            session()->put('url.intended', route('contact.index'));

            // Redirigimos al Login
            return redirect()->route('login');
        }

        // 🟢 B) LÓGICA PARA USUARIOS LOGUEADOS
        $this->checkCooldown();

        if (!$this->canSend) { 
            // Mensaje de error visual si intenta saltarse el bloqueo
            $this->addError('general', 'Debes esperar 24 horas entre consultas.');
            return; 
        }

        // Validamos. Nota: 'phone' es obligatorio en tu BD, así que debe estar lleno.
        $this->validate();

        try {
            $user = Auth::user();

            // Guardar en Base de Datos (Mapeamos 'motivo' a 'subject')
            $contact = Contact::create([
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $this->phone,
                'subject' => $this->motivo, // Mapeo
                'message' => $this->mensaje, // Mapeo
                'status'  => 'pendiente',
            ]);

            // Notificar a Admins
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NuevaConsultaNotification($contact));
            }

            // Éxito
            $this->reset(['motivo', 'mensaje']);
            $this->enviado = true;
            $this->canSend = false;

        } catch (\Exception $e) {
            $this->addError('general', 'Error al enviar. Intenta nuevamente.');
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}