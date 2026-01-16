<?php

namespace App\Livewire\Arena;

use Livewire\Component;
use App\Models\Lobby;
use App\Models\LobbySlot;
use App\Models\Cancha;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;

class LobbyRoom extends Component
{
    public $lobby;
    public $userSlot;
    
    // UI
    public $carouselItems;
    public $suggestedLobbies;

    public function mount(Lobby $lobby)
    {
        $this->lobby = $lobby;
        $this->userSlot = $this->lobby->slots()->where('user_id', Auth::id())->first();
        
        // Si no tiene slot, lo sacamos
        if (!$this->userSlot) {
            return redirect()->route('arena.index');
        }

        // Keep Alive
        if ($this->lobby->status === 'searching') {
            $this->lobby->update(['expires_at' => now()->addHours(48)]);
        }

        $this->loadEntertainment();
    }

    public function loadEntertainment()
    {
        // Carrusel
        $this->carouselItems = Cancha::where('district_id', $this->lobby->district_id)
            ->where('is_active', true)
            ->with(['media', 'district']) 
            ->inRandomOrder()
            ->take(5)
            ->get();

        // Lobby Hopper
        $this->suggestedLobbies = Lobby::where('id', '!=', $this->lobby->id)
            ->where('sport_id', $this->lobby->sport_id)
            ->where('status', 'searching')
            ->withCount('slots')
            ->take(3)
            ->get();
    }

    public function confirmAssistance()
    {
        if (!$this->userSlot) return;

        // Lógica Party
        $myTeam = TeamMember::where('user_id', Auth::id())->first();
        $friendIds = $myTeam 
            ? TeamMember::where('team_id', $myTeam->team_id)->pluck('user_id')->toArray()
            : [Auth::id()];

        $this->lobby->slots()
            ->whereIn('user_id', $friendIds)
            ->update(['confirmed_at' => now()]);

        $this->lobby->refresh();
    }

    // 🟢 NUEVA FUNCIÓN: SALIR REALMENTE DE LA SALA
    public function exitLobby()
    {
        if ($this->userSlot) {
            $this->userSlot->delete(); // Borra el registro
            
            // Si el lobby queda vacío, lo borramos (opcional, para limpieza)
            if ($this->lobby->slots()->count() === 0) {
                $this->lobby->delete();
            }
        }
        
        // Redirigir al buscador (ahora aparecerá limpio)
        return redirect()->route('arena.index');
    }

    public function render()
    {
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