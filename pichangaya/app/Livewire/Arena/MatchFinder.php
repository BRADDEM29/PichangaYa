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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->party_id && $user->party->leader_id !== $user->id) {
            session()->flash('error', 'Solo el líder del grupo puede buscar partida.');
            return;
        }

        $playersToJoin = $user->party_id ? $user->party->members : collect([$user]);
        
        // 🟢 CÁLCULO DE CAPACIDAD EXACTA POR DEPORTE
        $maxSlots = 14; 
        $sport = Sport::find($this->selectedSport);
        
        if ($sport) {
            $name = strtolower($sport->name);
            if (str_contains($name, 'tenis') || str_contains($name, 'padel') || str_contains($name, '1vs1')) {
                $maxSlots = 2; // Tenis = 2 jugadores
            } elseif (str_contains($name, 'futbol 5') || str_contains($name, 'fútbol 5') || str_contains($name, 'basket')) {
                $maxSlots = 10;
            } elseif (str_contains($name, 'voley') || str_contains($name, 'vóley')) {
                $maxSlots = 12;
            }
        }

        // Buscar lobby existente del tamaño correcto
        $lobby = Lobby::where('sport_id', $this->selectedSport)
            ->where('district_id', $this->selectedDistrict)
            ->where('status', 'searching')
            ->where('max_slots', $maxSlots)
            ->get()
            ->filter(function ($l) use ($playersToJoin) {
                return ($l->max_slots - $l->slots()->count()) >= $playersToJoin->count();
            })
            ->first();

        // Crear si no existe
        if (!$lobby) {
            $lobby = Lobby::create([
                'sport_id' => $this->selectedSport,
                'district_id' => $this->selectedDistrict,
                'status' => 'searching',
                'max_slots' => $maxSlots, // Se guarda el valor correcto (ej: 2)
                'expires_at' => now()->addHours(48),
                'created_by' => Auth::id()
            ]);
        } else {
            $lobby->update(['expires_at' => now()->addHours(48)]);
        }

        $this->insertPlayersToLobby($lobby, $playersToJoin, $maxSlots);

        return Redirect::route('lobby.show', $lobby->id);
    }

    private function insertPlayersToLobby($lobby, $players, $maxSlots)
    {
        $slotsPerTeam = intdiv($maxSlots, 2);

        foreach ($players as $player) {
            
            // Anti-Duplicados
            $exists = LobbySlot::where('lobby_id', $lobby->id)
                ->where('user_id', $player->id)
                ->exists();

            if ($exists) continue;

            $countA = $lobby->slots()->where('team_side', 'A')->count();
            $teamSide = ($countA < $slotsPerTeam) ? 'A' : 'B';

            LobbySlot::create([
                'lobby_id' => $lobby->id,
                'user_id' => $player->id,
                'team_side' => $teamSide,
                'is_captain' => ($lobby->slots()->count() === 0),
                'confirmed_at' => null
            ]);
        }
    }

    public function render()
    {
        return view('livewire.arena.match-finder');
    }
}