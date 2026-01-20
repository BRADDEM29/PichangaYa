<?php

namespace App\Livewire\Arena;

use Livewire\Component;
use App\Models\Lobby;
use App\Models\LobbySlot;
use App\Models\Cancha;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class LobbyRoom extends Component
{
    public $lobby;
    public $userSlot;
    
    // --- CHAT VARIABLES ---
    public $chatTab = 'general'; // 'general' o 'party'
    public $lobbyMessages = [];  // Mensajes Generales (A + B)
    public $partyMessages = [];  // Mensajes de tu Grupo
    public $newMessage = '';

    // --- UI ELEMENTS ---
    public $carouselItems;
    public $suggestedLobbies;

    public function mount(Lobby $lobby)
    {
        $this->lobby = $lobby;
        $this->userSlot = $this->lobby->slots()->where('user_id', Auth::id())->first();
        
        // Si no tiene slot, fuera
        if (!$this->userSlot) {
            return redirect()->route('arena.index');
        }

        // Keep Alive: Reiniciar timer a 48h
        if ($this->lobby->status === 'searching') {
            $this->lobby->update(['expires_at' => now()->addHours(48)]);
        }

        $this->loadEntertainment();
        $this->loadChat(); 
    }

    // 🟢 1. LÓGICA BOTÓN "LISTO" (READY)
    public function toggleReady()
    {
        if (!$this->userSlot) return;

        // Alternar entre Listo (confirmed_at = hora) y No Listo (confirmed_at = null)
        if ($this->userSlot->confirmed_at) {
            $this->userSlot->update(['confirmed_at' => null]);
        } else {
            $this->userSlot->update(['confirmed_at' => now()]);
        }
        
        $this->lobby->refresh();
    }

    // 🟢 2. LÓGICA CHAT DUAL (PESTAÑAS)
    public function setChatTab($tab)
    {
        $this->chatTab = $tab;
        $this->loadChat();
        $this->dispatch('scroll-lobby-chat'); // Bajar scroll al cambiar pestaña
    }

    public function loadChat()
    {
        // A. Cargar Chat General (Excluyendo mensajes de party privados)
        $this->lobbyMessages = Message::where('lobby_id', $this->lobby->id)
            ->whereNull('party_id') // Importante: Solo mensajes públicos
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // B. Cargar Chat de Grupo (Solo si el usuario tiene Party)
        $user = Auth::user();
        if ($user->party_id) {
            $this->partyMessages = Message::where('party_id', $user->party_id)
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $this->partyMessages = [];
        }
    }

    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string|max:200']);
        $user = Auth::user();

        if ($this->chatTab === 'party' && $user->party_id) {
            // ENVIAR A MI GRUPO
            Message::create([
                'sender_id' => $user->id,
                'party_id' => $user->party_id, // Se vincula al grupo
                // No ponemos lobby_id para que no salga en el general
                'content' => $this->newMessage,
                'type' => 'text'
            ]);
        } else {
            // ENVIAR A TODOS (GENERAL)
            Message::create([
                'sender_id' => $user->id,
                'lobby_id' => $this->lobby->id, // Se vincula a la sala
                'content' => $this->newMessage,
                'type' => 'text'
            ]);
        }

        $this->newMessage = '';
        $this->loadChat();
        $this->dispatch('scroll-lobby-chat'); 
    }

    // --- 🏟️ UI & ENTRETENIMIENTO ---
    public function loadEntertainment()
    {
        $this->carouselItems = Cancha::where('district_id', $this->lobby->district_id)
            ->where('is_active', true)
            ->with(['media', 'district'])
            ->inRandomOrder()->take(5)->get();
            
        $this->suggestedLobbies = Lobby::where('id', '!=', $this->lobby->id)
            ->where('sport_id', $this->lobby->sport_id)
            ->where('status', 'searching')
            ->withCount('slots')
            ->take(3)
            ->get();
    }

    // --- 🏃 EQUIPOS Y SALIDA ---
    public function switchTeam()
    {
        $this->userSlot->refresh();
        $newTeam = ($this->userSlot->team_side === 'A') ? 'B' : 'A';
        
        if ($this->lobby->slots()->where('team_side', $newTeam)->count() >= 7) return;
        
        $this->userSlot->update(['team_side' => $newTeam, 'is_captain' => false]);
        $this->lobby->refresh();
    }

    public function toggleCaptain()
    {
        $this->userSlot->refresh();
        if ($this->userSlot->is_captain) {
            $this->userSlot->update(['is_captain' => false]);
        } else {
            $exists = $this->lobby->slots()
                ->where('team_side', $this->userSlot->team_side)
                ->where('is_captain', true)
                ->exists();
            if (!$exists) $this->userSlot->update(['is_captain' => true]);
        }
        $this->lobby->refresh();
    }

    public function exitLobby()
    {
        $user = Auth::user();

        // Regla Dota: Líder saca a toda la party
        if ($user->party_id && $user->party->leader_id === $user->id) {
            $memberIds = $user->party->members->pluck('id');
            $this->lobby->slots()->whereIn('user_id', $memberIds)->delete();
        } else {
            if ($this->userSlot) $this->userSlot->delete();
        }
        
        if ($this->lobby->slots()->count() === 0) {
            $this->lobby->delete();
        }
        
        return redirect()->route('arena.index');
    }

    public function render()
    {
        $this->lobby->refresh();
        
        // Polling constante del chat (se ejecuta cada vez que el front hace ping)
        $this->loadChat(); 

        $playerCount = $this->lobby->slots()->count();
        $maxPlayers = 14; 

        if ($playerCount >= $maxPlayers && $this->lobby->status === 'searching') {
            $this->lobby->update(['status' => 'confirming']);
        }

        return view('livewire.arena.lobby-room', [
            'playerCount' => $playerCount,
            'maxPlayers' => $maxPlayers
        ]);
    }
}