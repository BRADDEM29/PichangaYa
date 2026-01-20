<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Party;
use App\Models\Message;
use App\Models\Friendship;
use Illuminate\Support\Str;

class SocialPanel extends Component
{
    public $isOpen = false;
    public $searchQuery = '';
    public $activeTab = 'friends'; // 'friends' o 'party'
    
    // Propiedades de Chat Privado
    public $activeChatUserId = null; 
    public $chatMessages = [];     
    public $newMessage = '';       

    // Propiedades de Party
    public $inviteCodeInput = '';

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if (!$this->isOpen) {
            $this->closeChat();
        }
    }

    // --- LÓGICA DE CHAT PRIVADO ---
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

    // --- LÓGICA DE PARTY (GRUPOS) ---
    public function createParty()
    {
        $user = Auth::user();
        if ($user->party_id) return;

        $party = Party::create([
            'leader_id' => $user->id,
            'invite_code' => strtoupper(Str::random(6)),
        ]);

        $user->update(['party_id' => $party->id]);
        $this->activeTab = 'party';
    }

    public function joinParty()
    {
        $party = Party::where('invite_code', strtoupper($this->inviteCodeInput))->first();
        if (!$party) {
            session()->flash('party_error', 'Código no válido');
            return;
        }
        Auth::user()->update(['party_id' => $party->id]);
        $this->inviteCodeInput = '';
        $this->activeTab = 'party';
    }

    public function leaveParty()
    {
        $user = Auth::user();
        $party = $user->party;
        if (!$party) return;

        $user->update(['party_id' => null]);
        if ($party->leader_id === $user->id) {
            User::where('party_id', $party->id)->update(['party_id' => null]);
            $party->delete();
        }
    }

    // --- GESTIÓN DE AMIGOS ---
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

    public function render()
    {
        $user = Auth::user();
        $myParty = $user->party ? $user->party->load('members') : null;
        $activeChatUser = $this->activeChatUserId ? User::find($this->activeChatUserId) : null;

        $searchResults = [];
        if (strlen($this->searchQuery) > 2) {
            $rawResults = User::where('name', 'like', "%{$this->searchQuery}%")
                ->where('id', '!=', $user->id)->take(5)->get();
            
            $searchResults = $rawResults->map(function($resUser) use ($user) {
                $resUser->friend_status = $user->friendshipStatus($resUser->id);
                return $resUser;
            });
        }

        return view('livewire.social.social-panel', [
            'friends' => $user->friends,
            'searchResults' => $searchResults,
            'myParty' => $myParty,
            'activeChatUser' => $activeChatUser,
            'activeLobby' => $user->currentLobbySlot?->lobby
        ]);
    }
}