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

        // Keep Alive: Renovar expiración si entra alguien
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

    // 🟢 NUEVO: CAMBIAR DE EQUIPO (A <-> B)
    public function switchTeam()
    {
        // Refrescamos la relación para tener datos frescos
        $this->userSlot->refresh();

        $currentSide = $this->userSlot->team_side;
        $targetSide = ($currentSide === 'A') ? 'B' : 'A';

        // 1. Verificar si el equipo destino está lleno (Máx 7)
        $countTarget = $this->lobby->slots()->where('team_side', $targetSide)->count();
        
        if ($countTarget >= 7) {
            // Podrías usar un dispatch browser event para una alerta JS, o session flash
            return; 
        }

        // 2. Realizar el cambio
        $this->userSlot->update([
            'team_side' => $targetSide,
            'is_captain' => false, // Pierde capitanía al cambiarse
        ]);
        
        $this->lobby->refresh();
    }

    // 🟢 NUEVO: SER LÍDER / DEJAR DE SERLO
    public function toggleCaptain()
    {
        $this->userSlot->refresh();

        // Si ya soy capitán, renuncio
        if ($this->userSlot->is_captain) {
            $this->userSlot->update(['is_captain' => false]);
            $this->lobby->refresh();
            return;
        }

        // Si quiero ser capitán, verifico que no haya otro en mi equipo
        $existingCaptain = $this->lobby->slots()
            ->where('team_side', $this->userSlot->team_side)
            ->where('is_captain', true)
            ->exists();

        if (!$existingCaptain) {
            $this->userSlot->update(['is_captain' => true]);
        }
        
        $this->lobby->refresh();
    }

    public function exitLobby()
    {
        if ($this->userSlot) {
            $this->userSlot->delete(); 
            
            if ($this->lobby->slots()->count() === 0) {
                $this->lobby->delete();
            }
        }
        
        return redirect()->route('arena.index');
    }

    public function render()
    {
        // Refrescamos datos antes de renderizar para el polling
        $this->lobby->refresh();
        
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