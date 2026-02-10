<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Models\LobbySlot;
use Illuminate\Support\Facades\DB; // Usaremos DB para consultar la tabla pivote directamente y evitar errores de modelos

class SocialPanel extends Component
{
    public $isOpen = false;
    public $searchQuery = '';
    
    // Chat Privado
    public $activeChatUserId = null; 
    public $chatMessages = [];     
    public $newMessage = '';       

    protected $listeners = ['refreshComponent' => '$refresh', 'scroll-bottom' => '$refresh'];

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if (!$this->isOpen) {
            $this->closeChat();
        }
    }

    // --- LÓGICA DE CHAT ---
    public function openChat($friendId)
    {
        $this->activeChatUserId = $friendId;
        $this->loadMessages();
        $this->dispatch('scroll-bottom'); 
    }

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

        // Asumiendo que tu modelo Message tiene sender_id y receiver_id
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

    // --- GESTIÓN DE AMIGOS (Restaurado) ---

    public function addFriend($targetId) 
    {
        $user = Auth::user();
        
        // Verificamos si ya existe alguna relación para no duplicar
        $exists = DB::table('friendships')
            ->where(function($q) use ($user, $targetId) {
                $q->where('user_id', $user->id)->where('friend_id', $targetId);
            })
            ->orWhere(function($q) use ($user, $targetId) {
                $q->where('user_id', $targetId)->where('friend_id', $user->id);
            })
            ->exists();

        if (!$exists) {
            // Insertamos directamente en la tabla pivote (friendships)
            DB::table('friendships')->insert([
                'user_id' => $user->id,      // El que envía
                'friend_id' => $targetId,    // El que recibe
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function acceptFriend($requesterId) 
    {
        $user = Auth::user();
        
        // Buscamos la solicitud donde YO soy el friend_id (el que recibe)
        DB::table('friendships')
            ->where('user_id', $requesterId)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'accepted', 'updated_at' => now()]);
    }

    // Helper para saber el estado de amistad con un usuario buscado
    private function getFriendshipStatus($targetId)
    {
        $myId = Auth::id();

        $friendship = DB::table('friendships')
            ->where(function($q) use ($myId, $targetId) {
                $q->where('user_id', $myId)->where('friend_id', $targetId);
            })
            ->orWhere(function($q) use ($myId, $targetId) {
                $q->where('user_id', $targetId)->where('friend_id', $myId);
            })
            ->first();

        if (!$friendship) return 'none';
        if ($friendship->status === 'accepted') return 'accepted';
        
        // Si está pendiente, hay que ver quién la mandó
        if ($friendship->status === 'pending') {
            return $friendship->user_id === $myId ? 'sent' : 'received';
        }

        return 'none';
    }

    public function render()
    {
        $user = Auth::user();
        $activeChatUser = $this->activeChatUserId ? User::find($this->activeChatUserId) : null;
        
        // 1. Buscar Usuarios (Solo si hay texto)
        $searchResults = [];
        if (strlen($this->searchQuery) > 2) {
            $rawResults = User::where('name', 'like', "%{$this->searchQuery}%")
                ->where('id', '!=', $user->id)
                ->take(5)
                ->get();
            
            // Añadimos el estado de amistad a cada resultado
            $searchResults = $rawResults->map(function($resUser) {
                $resUser->friend_status = $this->getFriendshipStatus($resUser->id);
                return $resUser;
            });
        }

        // 2. Obtener lista de amigos aceptados para mostrar en la lista
        // (Esto depende de cómo tengas definida la relación en tu modelo User,
        //  usualmente es belongsToMany). Si tienes 'friends', úsalo.
        // Si no, aquí hago una query manual rápida para asegurar que funcione:
        $friendsIds = DB::table('friendships')
            ->where('status', 'accepted')
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('friend_id', $user->id);
            })
            ->get()
            ->map(function($f) use ($user) {
                return $f->user_id === $user->id ? $f->friend_id : $f->user_id;
            });

        $friends = User::whereIn('id', $friendsIds)->get();

        // 3. Verificar Lobby
        $activeLobby = null;
        if ($user->currentLobbySlot) {
            $activeLobby = $user->currentLobbySlot->lobby;
        }

        return view('livewire.social.social-panel', [
            'friends' => $friends,
            'searchResults' => $searchResults,
            'activeChatUser' => $activeChatUser,
            'activeLobby' => $activeLobby
        ]);
    }
}