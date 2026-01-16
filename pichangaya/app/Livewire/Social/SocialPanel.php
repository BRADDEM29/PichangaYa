<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SocialPanel extends Component
{
    public $isOpen = false;
    public $searchQuery = '';
    
    // Variables para el Lobby Activo
    public $activeLobby = null;

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        $user = Auth::user();

        // 1. VERIFICAR SI ESTOY EN UN LOBBY (Para mostrar el botón verde)
        // Usamos la relación que creamos en User.php
        $this->activeLobby = $user->currentLobbySlot ? $user->currentLobbySlot->lobby : null;

        // 2. Obtener Amigos
        $friends = User::whereHas('friendsOfMine', function($q) use ($user) {
            $q->where('friend_id', $user->id)
              ->where('friendships.status', 'accepted');
        })->orWhereHas('friendOf', function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('friendships.status', 'accepted');
        })->get();

        // 3. Resultados de búsqueda
        $searchResults = [];
        if (strlen($this->searchQuery) > 2) {
            $searchResults = User::where('name', 'like', "%{$this->searchQuery}%")
                ->where('id', '!=', $user->id)
                ->take(5)->get();
        }

        return view('livewire.social.social-panel', [
            'friends' => $friends,
            'searchResults' => $searchResults
        ]);
    }

    public function addFriend($friendId)
    {
        $user = Auth::user();
        
        // Evitar duplicados
        $exists = \DB::table('friendships')->where(function($q) use ($user, $friendId){
            $q->where('user_id', $user->id)->where('friend_id', $friendId);
        })->orWhere(function($q) use ($user, $friendId){
            $q->where('user_id', $friendId)->where('friend_id', $user->id);
        })->exists();

        if (!$exists) {
            $user->friendsOfMine()->attach($friendId, ['status' => 'pending']);
            session()->flash('message', 'Solicitud enviada.');
        }
    }
}