<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FavoritesList extends Component
{
    protected $listeners = ['refresh-favorites' => '$refresh']; 

    public function removeFavorite($canchaId)
    {
        $user = Auth::user();
        if ($user) {
            $user->favorites()->detach($canchaId);
            
            // ESTA ES LA LÍNEA NUEVA IMPORTANTE:
            // Envía un evento al navegador diciendo "Hey, quité la cancha con este ID"
            $this->dispatch('favorite-removed', id: $canchaId);
        }
    }

    public function render()
    {
        $favorites = collect();
        if (Auth::check()) {
            $favorites = Auth::user()->favorites()->get();
        }

        return view('livewire.home.favorites-list', [
            'favorites' => $favorites
        ]);
    }
}