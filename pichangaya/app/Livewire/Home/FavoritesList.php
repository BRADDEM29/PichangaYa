<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Auth;

class FavoritesList extends Component
{
    /**
     * Escucha el refresco pedido por el Grid.
     * Cuando el Grid hace el fetch exitoso, dispara este evento 
     * para que esta lista se vuelva a renderizar con los nuevos datos.
     */
    #[On('refresh-favorites')]
    public function refresh() 
    {
        // No necesita código, el atributo #[On] fuerza el re-renderizado
    }

    /**
     * Elimina una cancha de favoritos desde la lista superior.
     */
    public function removeFavorite($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Quitamos la relación en la base de datos
            $user->favorites()->detach($id);
            
            /**
             * LA MAGIA DE LA SINCRONIZACIÓN:
             * Despachamos un evento al navegador. 
             * El Alpine.js del Grid está escuchando '@favorite-removed.window'
             * y cambiará el estado del corazón a 'false' automáticamente.
             */
            $this->dispatch('favorite-removed', id: $id);
        }
    }

    public function render()
    {
        return view('livewire.home.favorites-list', [
            'favorites' => Auth::check() ? Auth::user()->favorites()->get() : collect()
        ]);
    }
}