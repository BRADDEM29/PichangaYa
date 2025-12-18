<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ContactForm extends Component
{
    // Declaramos todas las variables como públicas para que Livewire las reconozca
    public $name;
    public $email;
    public $subject;
    public $message;
    public $successMessage = ''; // Inicializada vacía para evitar errores de undefined

    // Reglas de validación
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        // 1. Validar los datos según las reglas arriba definidas
        $this->validate();

        // 2. Intentar guardar en la base de datos
        try {
            DB::table('contacts')->insert([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Resetear los campos después del éxito
            $this->reset(['name', 'email', 'subject', 'message']);

            // 4. Activar el mensaje de éxito
            $this->successMessage = '¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.';

        } catch (\Exception $e) {
            // Opcional: manejar errores de base de datos aquí
            session()->flash('error', 'Hubo un problema al enviar el mensaje.');
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}