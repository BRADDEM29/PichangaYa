<?php

namespace App\Livewire\Arena;

use Livewire\Component;
use App\Models\Sport;
use App\Models\District;
use App\Models\Lobby;
use App\Models\LobbySlot;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MatchFinder extends Component
{
    // Variables del formulario
    public $mode = 'casual'; 
    public $selectedSport;
    public $selectedDistrict;

    // Colecciones para los selectores
    public $sports;
    public $districts;

    public function mount()
    {
        $this->sports = Sport::all();
        $this->districts = District::all();

        // Valores por defecto
        $this->selectedSport = $this->sports->first()->id ?? null;
        $this->selectedDistrict = $this->districts->first()->id ?? null;
    }

    public function setMode($newMode)
    {
        $this->mode = $newMode;
    }

    /**
     * 🟢 LÓGICA MAESTRA: BÚSQUEDA GRUPAL Y PERSISTENTE
     * Basado en la Propuesta V2.0 (Matchmaking Persistente)
     */
    public function searchMatch()
    {
        // 1. VALIDACIÓN DE SESIÓN
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. REGLA DE NEGOCIO: LÍDER DE PARTY 
        // Solo el líder puede iniciar la búsqueda para todo el grupo.
        if ($user->party_id && $user->party->leader_id !== $user->id) {
            session()->flash('error', 'Solo el líder del grupo puede iniciar la búsqueda de partida.');
            return;
        }

        // 3. DEFINIR GRUPO DE JUGADORES (Arrastrar amigos) 
        // Si no tiene party, es una búsqueda individual (Solo).
        $playersToJoin = $user->party_id 
            ? $user->party->members 
            : collect([$user]);

        $neededSlots = $playersToJoin->count();

        // 4. VALIDAR SI ALGUIEN YA ESTÁ EN OTRA PARTIDA [cite: 54, 106]
        foreach ($playersToJoin as $player) {
            $activeMatch = LobbySlot::where('user_id', $player->id)
                ->whereHas('lobby', function ($q) {
                    $q->whereIn('status', ['searching', 'confirming']);
                })->exists();

            if ($activeMatch) {
                session()->flash('error', "El jugador {$player->name} ya está en una búsqueda activa.");
                return;
            }
        }

        // 5. LÓGICA RANKED (BETA) [cite: 28]
        if ($this->mode === 'ranked') {
            // El requisito es ser capitán de un equipo validado
            session()->flash('error', 'El modo Torneo requiere un Equipo Completo y Validado.');
            return;
        }

        // 6. BUSCAR LOBBY EXISTENTE (Matchmaking Persistente) [cite: 30, 80]
        $lobby = Lobby::where('sport_id', $this->selectedSport)
            ->where('district_id', $this->selectedDistrict)
            ->where('status', 'searching')
            ->where('expires_at', '>', now())
            ->get()
            ->filter(function ($l) use ($neededSlots) {
                // Verificamos espacio disponible (Máximo 14 para Fútbol 7) [cite: 32, 41]
                return ($l->slots()->count() + $neededSlots) <= 14;
            })
            ->first();

        // 7. FASE DE CREACIÓN O UNIÓN 
        if (!$lobby) {
            // Fase 1: Creación de Lobby nuevo con Timer de 48h 
            $lobby = $this->createLobby();
        } else {
            // Evento "Keep Alive": Reiniciar timer a 48h al entrar nuevo jugador/grupo [cite: 34, 36, 96]
            $lobby->update(['expires_at' => now()->addHours(48)]);
        }

        // 8. INSERTAR A TODOS LOS MIEMBROS [cite: 18]
        $this->insertPlayersToLobby($lobby, $playersToJoin);

        // 9. REDIRECCIÓN
        // El líder es redirigido; los miembros lo verán por el polling de su panel social
        return redirect()->route('lobby.show', $lobby->id);
    }

    private function createLobby()
    {
        return Lobby::create([
            'sport_id' => $this->selectedSport,
            'district_id' => $this->selectedDistrict,
            'status' => 'searching',
            'expires_at' => now()->addHours(48), // Timer inicial de 48 horas [cite: 33, 83]
        ]);
    }

    private function insertPlayersToLobby($lobby, $players)
    {
        foreach ($players as $player) {
            // Lógica de balanceo: Llenar equipo A (7), luego B (7) [cite: 41]
            $countA = $lobby->slots()->where('team_side', 'A')->count();
            $teamSide = ($countA < 7) ? 'A' : 'B';

            LobbySlot::create([
                'lobby_id' => $lobby->id,
                'user_id' => $player->id,
                'team_side' => $teamSide,
                // Es capitán de sala solo si es el primer slot absoluto
                'is_captain' => ($lobby->slots()->count() === 0),
                // Si vienen en Party, se marcan como auto-confirmados para agilizar 
                'confirmed_at' => now(), 
            ]);
        }
    }

    public function render()
    {
        return view('livewire.arena.match-finder');
    }
}