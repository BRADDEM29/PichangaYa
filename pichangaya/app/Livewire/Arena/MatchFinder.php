<?php

namespace App\Livewire\Arena;

use Livewire\Component;
use App\Models\Sport;
use App\Models\District;
use App\Models\Lobby;
use App\Models\LobbySlot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MatchFinder extends Component
{
    public $mode = 'casual'; 
    public $selectedSport;
    public $selectedDistrict;

    public $sports;
    public $districts;

    public function mount()
    {
        $this->sports = Sport::all();
        $this->districts = District::all();

        // Valores por defecto
        if($this->sports->isNotEmpty()){
            $this->selectedSport = $this->sports->first()->id;
        }
        if($this->districts->isNotEmpty()){
            $this->selectedDistrict = $this->districts->first()->id;
        }
    }

    public function setMode($newMode)
    {
        $this->mode = $newMode;
    }

    public function searchMatch()
    {
        // 1. VALIDACIÓN
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Validación de Party
        if ($user->party_id && $user->party->leader_id !== $user->id) {
            session()->flash('error', 'Solo el líder del grupo puede buscar partida.');
            return;
        }

        // Definir jugadores a unir
        $playersToJoin = $user->party_id ? $user->party->members : collect([$user]);
        $neededSlots = $playersToJoin->count();

        // 🟢 2. OBTENER INFORMACIÓN DEL DEPORTE (Aquí está la magia)
        $sportModel = Sport::find($this->selectedSport);
        
        if (!$sportModel) {
            session()->flash('error', 'Deporte no válido.');
            return;
        }

        // Leemos la capacidad DIRECTAMENTE de la base de datos
        // Si es Tenis traerá 2, si es Fútbol 7 traerá 14.
        $maxSlots = $sportModel->total_players; 

        // 3. BUSCAR LOBBY EXISTENTE
        $lobby = Lobby::where('sport_id', $this->selectedSport)
            ->where('district_id', $this->selectedDistrict)
            ->where('status', 'searching')
            ->where('expires_at', '>', now())
            ->get()
            ->filter(function ($l) use ($neededSlots, $maxSlots) {
                // Verificar si caben los jugadores (Slots ocupados + Nuevos <= Max)
                return ($l->slots()->count() + $neededSlots) <= $maxSlots;
            })
            ->first();

        // 4. CREAR SI NO EXISTE
        if (!$lobby) {
            $lobby = Lobby::create([
                'sport_id' => $this->selectedSport,
                'district_id' => $this->selectedDistrict,
                'status' => 'searching',
                'slots_count' => 0, 
                'max_slots' => $maxSlots, // Guardamos el valor correcto en el Lobby
                'expires_at' => now()->addHours(48),
                'created_by' => Auth::id()
            ]);
        } else {
            // Keep Alive
            $lobby->update(['expires_at' => now()->addHours(48)]);
        }

        // 5. INSERTAR JUGADORES
        $this->insertPlayersToLobby($lobby, $playersToJoin, $maxSlots);

        return Redirect::route('lobby.show', $lobby->id);
    }

    private function insertPlayersToLobby($lobby, $players, $maxSlots)
    {
        // Calcular mitad para dividir equipos (Ej: 14/2 = 7)
        $slotsPerTeam = intdiv($maxSlots, 2);

        foreach ($players as $player) {
            // Balanceo automático
            $countA = $lobby->slots()->where('team_side', 'A')->count();
            // Si el equipo A no está lleno, va al A, sino al B
            $teamSide = ($countA < $slotsPerTeam) ? 'A' : 'B';

            LobbySlot::create([
                'lobby_id' => $lobby->id,
                'user_id' => $player->id,
                'team_side' => $teamSide,
                'is_captain' => ($lobby->slots()->count() === 0),
                'confirmed_at' => now(), 
            ]);
        }
    }

    public function render()
    {
        return view('livewire.arena.match-finder');
    }
}