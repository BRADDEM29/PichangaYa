<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuggestionReceived;

class SuggestionForm extends Component
{
    public $name, $rating = 5, $comment, $successMessage;

    public function submit()
    {
        // 1. Verificar si está registrado
        if (!Auth::check()) {
            return redirect()->route('register');
        }

        // 2. Verificar límite de 24 horas
        $lastSuggestion = DB::table('suggestions')
            ->where('user_id', Auth::id())
            ->where('created_at', '>', now()->subDay())
            ->first();

        if ($lastSuggestion) {
            session()->flash('error', 'Solo puedes enviar una sugerencia cada 24 horas. ¡Gracias por tu paciencia!');
            return;
        }

        $this->validate(['comment' => 'required|min:5', 'rating' => 'required|integer']);

        $data = [
            'name' => Auth::user()->name,
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('suggestions')->insert($data);

        // 3. Enviar Email
        Mail::to('pichangayacusco@gmail.com')->send(new SuggestionReceived($data));

        $this->reset(['comment', 'rating']);
        $this->successMessage = '¡Sugerencia enviada con éxito! Te enviamos un aviso al correo.';
    }

    public function render()
    {
        return view('livewire.suggestion-form');
    }
}