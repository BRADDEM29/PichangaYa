<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Models\Friendship;

class SocialPanel extends Component
{
    public $isOpen = false;
    public $searchQuery = '';
    public $activeLobby = null;

    // 🟢 CAMBIO: Usamos ID en lugar del objeto completo para evitar errores
    public $activeChatUserId = null; 
    
    public $chatMessages = [];     
    public $newMessage = '';       

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if (!$this->isOpen) {
            $this->closeChat();
        }
    }

    // Abrir conversación
    public function openChat($friendId)
    {
        // 1. Guardamos solo el ID
        $this->activeChatUserId = $friendId;
        
        // 2. Cargamos mensajes
        $this->loadMessages();
        
        // 3. Forzamos scroll abajo
        $this->dispatch('scroll-bottom'); 
    }

    // Cerrar conversación
    public function closeChat()
    {
        $this->activeChatUserId = null;
        $this->chatMessages = [];
        $this->newMessage = '';
    }

    public function loadMessages()
    {
        if (!$this->activeChatUserId) return;

        $myId = Auth::id();
        $friendId = $this->activeChatUserId;

        $this->chatMessages = Message::where(function($q) use ($myId, $friendId) {
                $q->where('sender_id', $myId)->where('receiver_id', $friendId);
            })
            ->orWhere(function($q) use ($myId, $friendId) {
                $q->where('sender_id', $friendId)->where('receiver_id', $myId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string|max:1000']);

        if ($this->activeChatUserId) {
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->activeChatUserId,
                'content' => $this->newMessage
            ]);

            $this->newMessage = '';
            $this->loadMessages();
            $this->dispatch('scroll-bottom');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $this->activeLobby = $user->currentLobbySlot ? $user->currentLobbySlot->lobby : null;

        // 🟢 Recuperamos el objeto usuario AQUÍ para la vista
        $activeChatUser = $this->activeChatUserId ? User::find($this->activeChatUserId) : null;

        $friends = $user->friends; 

        $searchResults = [];
        if (strlen($this->searchQuery) > 2) {
            $rawResults = User::where('name', 'like', "%{$this->searchQuery}%")
                ->where('id', '!=', $user->id)
                ->take(5)->get();
            
            $searchResults = $rawResults->map(function($resUser) use ($user) {
                $resUser->friend_status = $user->friendshipStatus($resUser->id);
                return $resUser;
            });
        }

        return view('livewire.social.social-panel', [
            'friends' => $friends,
            'searchResults' => $searchResults,
            'activeChatUser' => $activeChatUser // Pasamos el usuario a la vista
        ]);
    }

    public function addFriend($friendId) {
        $user = Auth::user();
        if ($user->friendshipStatus($friendId) === 'none') {
            $user->friendsOfMine()->attach($friendId, ['status' => 'pending']);
        }
    }

    public function acceptFriend($friendId) {
        $user = Auth::user();
        $friendship = Friendship::where('user_id', $friendId)
            ->where('friend_id', $user->id)->where('status', 'pending')->first();
        if ($friendship) $friendship->update(['status' => 'accepted']);
    }
}