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
    // Variables del formulario (conectadas con la vista)
    public $mode = 'casual'; // 'casual' o 'ranked'
    public $selectedSport;
    public $selectedDistrict;

    // Colecciones para los selectores
    public $sports;
    public $districts;

    public function mount()
    {
        // Cargar datos iniciales
        $this->sports = Sport::all();
        $this->districts = District::all();

        // Valores por defecto (el primero de la lista)
        $this->selectedSport = $this->sports->first()->id ?? null;
        $this->selectedDistrict = $this->districts->first()->id ?? null;
    }

    public function setMode($newMode)
    {
        $this->mode = $newMode;
    }

    public function searchMatch()
    {
        // 1. VALIDACIÓN BÁSICA
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. VERIFICAR SI YA ESTÁ EN UN LOBBY (Para no duplicar)
        // Buscamos si tiene un slot activo en un lobby que no haya terminado
        $currentSlot = LobbySlot::where('user_id', $user->id)
            ->whereHas('lobby', function ($q) {
                $q->whereIn('status', ['searching', 'confirming']);
            })->first();

        if ($currentSlot) {
            // Si ya está buscando, lo mandamos directo a su sala
            return redirect()->route('lobby.show', $currentSlot->lobby_id);
        }

        // 3. LÓGICA DEL MATCHMAKING (CASUAL)
        if ($this->mode === 'casual') {
            
            // A. Buscar un lobby existente con hueco (que coincida Deporte y Distrito)
            $existingLobby = Lobby::where('sport_id', $this->selectedSport)
                ->where('district_id', $this->selectedDistrict)
                ->where('status', 'searching')
                ->where('expires_at', '>', now())
                ->get()
                ->filter(function ($lobby) {
                    // Filtramos en PHP los que tengan espacio (ej. menos de 14)
                    // NOTA: Asumimos 14 para Fútbol 7, esto deberia venir de la tabla Sports
                    return $lobby->player_count < 14; 
                })
                ->first();

            if ($existingLobby) {
                // --> UNIRSE A LOBBY EXISTENTE
                $this->joinLobby($existingLobby, $user);
                return redirect()->route('lobby.show', $existingLobby->id);
            } else {
                // --> CREAR NUEVO LOBBY
                $newLobby = $this->createLobby($user);
                return redirect()->route('lobby.show', $newLobby->id);
            }
        }

        // 4. LÓGICA RANKED (PENDIENTE)
        if ($this->mode === 'ranked') {
            // Aquí iría la validación de si es capitán de equipo
            session()->flash('error', 'El modo competitivo requiere un Equipo registrado.');
        }
    }

    private function createLobby($user)
    {
        // Crear la sala
        $lobby = Lobby::create([
            'sport_id' => $this->selectedSport,
            'district_id' => $this->selectedDistrict,
            'status' => 'searching',
            'expires_at' => now()->addHours(48), // Regla de las 48h
        ]);

        // Crear el primer slot (Capitán de sala)
        LobbySlot::create([
            'lobby_id' => $lobby->id,
            'user_id' => $user->id,
            'is_captain' => true,
            'team_side' => 'A' // Por defecto entra al lado A
        ]);

        return $lobby;
    }

    private function joinLobby($lobby, $user)
    {
        // Reiniciar el contador de 48h (Keep Alive)
        $lobby->update(['expires_at' => now()->addHours(48)]);

        // Determinar lado (Lógica simple: llenar A, luego B)
        $countA = $lobby->slots()->where('team_side', 'A')->count();
        $side = ($countA < 7) ? 'A' : 'B'; // Asumiendo 7 vs 7

        // Crear slot
        LobbySlot::create([
            'lobby_id' => $lobby->id,
            'user_id' => $user->id,
            'is_captain' => false,
            'team_side' => $side
        ]);
    }

    public function render()
    {
        return view('livewire.arena.match-finder');
    }
}