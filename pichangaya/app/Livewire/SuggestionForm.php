<?php
// UBICACIÓN: C:\laragon\www\PichangaYa\pichangaya\app\Livewire\SuggestionForm.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Suggestion; // Modelo Eloquent
use App\Models\User;       // Para buscar admins
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification; // Facade para notificaciones
use App\Notifications\NuevaSugerenciaNotification; // Tu clase de notificación

class SuggestionForm extends Component
{
    public $rating = 5;
    public $comment;
    public $successMessage;
    public $canSend = true; // Variable para controlar el bloqueo de 24h

    public function mount()
    {
        // Verificamos si el usuario ya envió sugerencia en las últimas 24h al cargar
        if (Auth::check()) {
            $this->checkCooldown();
        }
    }

    public function setRating($val)
    {
        $this->rating = $val;
    }

    public function checkCooldown()
    {
        // Buscamos la última sugerencia de este usuario en las últimas 24 horas
        $lastSuggestion = Suggestion::where('user_id', Auth::id())
            ->where('created_at', '>', now()->subDay())
            ->exists(); // Usamos exists() que es más rápido

        // Si existe, NO puede enviar ($canSend = false)
        $this->canSend = !$lastSuggestion;
    }

    public function submit()
    {
        // 1. Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('register');
        }

        // 2. Verificar Cooldown (Seguridad Backend)
        $this->checkCooldown();
        if (!$this->canSend) {
            $this->addError('comment', 'Solo puedes enviar una sugerencia cada 24 horas.');
            return;
        }

        // 3. Validación (Incluye el límite de ~200 palabras/1500 caracteres)
        $this->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|min:5|max:1500', 
        ], [
            'comment.required' => 'Por favor cuéntanos un poco más.',
            'comment.max'      => 'El comentario es muy largo, por favor resúmelo (máx 200 palabras).',
        ]);

        try {
            // 4. Crear la sugerencia en BD
            $suggestion = Suggestion::create([
                'user_id' => Auth::id(),
                'name'    => Auth::user()->name,
                'rating'  => $this->rating,
                'comment' => $this->comment,
                'status'  => 'pendiente'
            ]);

            // 🟢 5. ENVIAR NOTIFICACIÓN A ADMINS (Campanita)
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new NuevaSugerenciaNotification($suggestion));
            }

            // 6. Resetear y Mensaje de éxito
            $this->reset(['comment']);
            $this->rating = 5;
            $this->successMessage = '¡Gracias por tu opinión! Nos ayuda a mejorar.';
            
            // Bloqueamos el formulario inmediatamente después de enviar
            $this->canSend = false;

        } catch (\Exception $e) {
            $this->successMessage = 'Hubo un error al enviar tu sugerencia. Intenta más tarde.';
        }
    }

    public function render()
    {
        return view('livewire.suggestion-form');
    }
}