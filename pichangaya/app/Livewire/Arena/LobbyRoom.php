<?php

namespace App\Livewire\Arena;

use Livewire\Component;
use App\Models\Lobby;
use App\Models\LobbySlot;
use App\Models\Cancha;
use App\Models\TeamMember; // Para lógica de grupo
use Illuminate\Support\Facades\Auth;

class LobbyRoom extends Component
{
    public $lobby;
    public $userSlot;
    
    // Variables de Entretenimiento UI
    public $nearbyCanchas;
    public $suggestedLobbies;

    public function mount(Lobby $lobby)
    {
        $this->lobby = $lobby;
        
        // Verificar si el usuario está en la sala
        $this->userSlot = $this->lobby->slots()->where('user_id', Auth::id())->first();
        
        if (!$this->userSlot) {
            // Si no está, lo redirigimos (Seguridad)
            return redirect()->route('arena.index');
        }

        // --- REGLA: "KEEP ALIVE" (PDF 2.2 Fase 1.3) ---
        // CADA VEZ que alguien entra, reiniciamos el reloj a 48h
        if ($this->lobby->status === 'searching') {
            $this->lobby->update(['expires_at' => now()->addHours(48)]);
        }

        $this->loadEntertainment();
    }

    public function loadEntertainment()
    {
        // 1. Carrusel de Canchas (Del mismo distrito)
        $this->nearbyCanchas = Cancha::where('district_id', $this->lobby->district_id)
            ->where('is_active', true)
            ->with('media')
            ->inRandomOrder()
            ->take(4)
            ->get();

        // 2. Lobby Hopper (Otras salas buscando)
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

        // --- REGLA: CONFIRMACIÓN "UNO POR TODOS" (PDF Fase 3) ---
        
        // 1. Buscamos si el usuario pertenece a un Equipo (Party)
        // Usamos la tabla team_members para ver si tiene compañeros en esta misma sala
        $myTeam = TeamMember::where('user_id', Auth::id())->first();
        
        $friendIds = [];
        if ($myTeam) {
            // Obtenemos los IDs de los compañeros de equipo
            $friendIds = TeamMember::where('team_id', $myTeam->team_id)
                ->pluck('user_id')
                ->toArray();
        } else {
            // Si no tiene equipo, es él solo
            $friendIds = [Auth::id()];
        }

        // 2. Confirmamos a TODOS los que estén en este lobby y sean del grupo
        $this->lobby->slots()
            ->whereIn('user_id', $friendIds)
            ->update(['confirmed_at' => now()]);

        // Refrescar para ver los cambios visuales
        $this->lobby->refresh();
    }

    public function render()
    {
        // --- REGLA: FULL HOUSE (14/14) ---
        $playerCount = $this->lobby->slots()->count();
        $maxPlayers = 14; 

        // Si se llena, pasamos a estado CONFIRMING
        if ($playerCount >= $maxPlayers && $this->lobby->status === 'searching') {
            $this->lobby->update(['status' => 'confirming']);
            // Aquí se dispararían las notificaciones
        }

        return view('livewire.arena.lobby-room', [
            'playerCount' => $playerCount,
            'maxPlayers' => $maxPlayers
        ]);
    }
}