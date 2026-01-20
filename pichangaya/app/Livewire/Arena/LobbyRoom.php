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
    
    // Chat de Sala
    public $lobbyMessages = [];
    public $newMessage = '';

    // UI Elements (Fase 1: Entretenimiento)
    public $carouselItems;
    public $suggestedLobbies;

    public function mount(Lobby $lobby)
    {
        $this->lobby = $lobby;
        $this->userSlot = $this->lobby->slots()->where('user_id', Auth::id())->first();
        
        // Si el usuario no tiene un slot en esta sala, redirigir a la arena
        if (!$this->userSlot) {
            return redirect()->route('arena.index');
        }

        // 🟢 Regla Keep Alive: Reiniciar timer a 48h al entrar/actualizar 
        if ($this->lobby->status === 'searching') {
            $this->lobby->update(['expires_at' => now()->addHours(48)]);
        }

        $this->loadEntertainment();
        $this->loadChat(); 
    }

    // --- 💬 LÓGICA DE CHAT DE SALA (Punto 1.2.4) --- 
    public function loadChat()
    {
        $this->lobbyMessages = Message::where('lobby_id', $this->lobby->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string|max:200']);

        Message::create([
            'sender_id' => Auth::id(),
            'lobby_id' => $this->lobby->id, 
            'content' => $this->newMessage,
            'type' => 'text'
        ]);

        $this->newMessage = '';
        $this->loadChat();
        $this->dispatch('scroll-lobby-chat'); 
    }

    // --- 🏟️ FASE 1: ENTRETENIMIENTO (UI) --- [cite: 37, 38, 39]
    public function loadEntertainment()
    {
        // Carrusel de Canchas compatibles [cite: 38]
        $this->carouselItems = Cancha::where('district_id', $this->lobby->district_id)
            ->where('is_active', true)
            ->with(['media', 'district'])
            ->inRandomOrder()
            ->take(5)
            ->get();
            
        // Lobby Hopper: Otros lobbys recomendados [cite: 39]
        $this->suggestedLobbies = Lobby::where('id', '!=', $this->lobby->id)
            ->where('sport_id', $this->lobby->sport_id)
            ->where('status', 'searching')
            ->withCount('slots')
            ->take(3)
            ->get();
    }

    // --- 🤝 FASE 3: CONFIRMACIÓN "UNO POR TODOS" --- [cite: 49, 51]
    public function confirmAssistance()
    {
        if (!$this->userSlot) return;

        $user = Auth::user();

        // Si el usuario está en una Party, confirmar a todos automáticamente 
        if ($user->party_id) {
            $memberIds = $user->party->members->pluck('id');
            $this->lobby->slots()
                ->whereIn('user_id', $memberIds)
                ->update(['confirmed_at' => now()]);
        } else {
            // Confirmación individual
            $this->userSlot->update(['confirmed_at' => now()]);
        }

        $this->lobby->refresh();
    }

    // --- 🏃 LÓGICA DE EQUIPOS Y SALIDA ---
    public function switchTeam()
    {
        $this->userSlot->refresh();
        $newTeam = ($this->userSlot->team_side === 'A') ? 'B' : 'A';
        
        // Límite de 7 jugadores por equipo (Fútbol 7) [cite: 32]
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

    // 🟢 SALIDA GRUPAL (SISTEMA DOTA/PARTY) [cite: 18, 54]
    public function exitLobby()
    {
        $user = Auth::user();

        // Regla: Si el líder sale, saca a todo su grupo del lobby 
        if ($user->party_id && $user->party->leader_id === $user->id) {
            $memberIds = $user->party->members->pluck('id');
            $this->lobby->slots()->whereIn('user_id', $memberIds)->delete();
        } else {
            // Salida individual
            if ($this->userSlot) $this->userSlot->delete();
        }
        
        // Limpieza: Si la sala se queda vacía, se elimina
        if ($this->lobby->slots()->count() === 0) {
            $this->lobby->delete();
        }
        
        return redirect()->route('arena.index');
    }

    public function render()
    {
        $this->lobby->refresh();
        $this->loadChat(); 

        $playerCount = $this->lobby->slots()->count();
        $maxPlayers = 14; 

        // Fase 2: Al llegar a 14/14, pasar a Confirmación [cite: 41, 43]
        if ($playerCount >= $maxPlayers && $this->lobby->status === 'searching') {
            $this->lobby->update(['status' => 'confirming']);
        }

        return view('livewire.arena.lobby-room', [
            'playerCount' => $playerCount,
            'maxPlayers' => $maxPlayers
        ]);
    }
}