<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class FavoritesList extends Component
{
    // Escucha cuando se agrega o quita un favorito desde cualquier parte
    #[On('refresh-favorites')]
    #[On('favorite-updated')]
    public function refreshList()
    {
        // Solo refresca el componente
    }

    public function removeFavorite($canchaId)
    {
        $user = Auth::user();
        if ($user) {
            $user->favorites()->detach($canchaId);
            
            // Notifica al resto de la aplicación (al Grid por ejemplo)
            $this->dispatch('favorite-removed', id: $canchaId);
            $this->dispatch('favorite-updated');
        }
    }

    public function render()
    {
        $favorites = Auth::check() ? Auth::user()->favorites()->get() : collect();

        return view('livewire.home.favorites-list', [
            'favorites' => $favorites
        ]);
    }
}