<?php

namespace App\Livewire\Arena;

use Livewire\Component;
use App\Models\District;
use App\Models\Sport;
use App\Models\Lobby;
use App\Models\LobbySlot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Arena extends Component
{
    public $district_id;
    public $sport_id;
    public $selectedDate;

    // Estado del usuario (si tiene lobby activo)
    public $activeLobbyId = null;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->sport_id = Sport::first()->id ?? 1;
        $this->district_id = District::first()->id ?? 1;

        // Verificar estado inmediatamente
        $this->checkUserStatus();

        // 🟢 CAMBIO: Si ya tiene lobby, REDIRIGIR INMEDIATAMENTE
        if ($this->activeLobbyId) {
            return redirect()->route('lobby.show', $this->activeLobbyId);
        }
    }

    public function checkUserStatus()
    {
        // Consulta directa para ver si está en una partida activa
        $lobbyEncontrado = DB::table('lobby_slots')
            ->join('lobbies', 'lobby_slots.lobby_id', '=', 'lobbies.id')
            ->where('lobby_slots.user_id', Auth::id())
            ->whereIn('lobbies.status', ['searching', 'confirming'])
            ->select('lobbies.id')
            ->orderBy('lobbies.created_at', 'desc')
            ->first();

        $this->activeLobbyId = $lobbyEncontrado ? $lobbyEncontrado->id : null;
    }

    public function startSearch()
    {
        if (Auth::user()->is_blocked) {
            session()->flash('error', 'Tu cuenta está bloqueada.');
            return;
        }

        // Doble verificación antes de crear
        $this->checkUserStatus();
        if ($this->activeLobbyId) {
            return;
        }

        // 1. Buscar Lobby existente
        $lobby = Lobby::where('district_id', $this->district_id)
            ->where('sport_id', $this->sport_id)
            ->where('status', 'searching')
            ->first();

        // 2. Crear lobby si no existe
        if (!$lobby) {
            $lobby = Lobby::create([
                'sport_id' => $this->sport_id,
                'district_id' => $this->district_id,
                'status' => 'searching',
                'scheduled_at' => $this->selectedDate . ' 20:00:00',
                'expires_at' => now()->addHours(48),
            ]);
        }

        // 3. Crear slot del usuario
        LobbySlot::create([
            'lobby_id' => $lobby->id,
            'user_id' => Auth::id(),
            'team_side' => rand(0, 1) ? 'A' : 'B',
            'is_captain' => false,
            'confirmed_at' => now(),
        ]);

        return redirect()->route('lobby.show', $lobby->id);
    }

    public function cancelSearch()
    {
        if ($this->activeLobbyId) {
            // Eliminar slot del usuario
            LobbySlot::where('lobby_id', $this->activeLobbyId)
                ->where('user_id', Auth::id())
                ->delete();

            // Si el lobby queda vacío, eliminarlo
            $remaining = LobbySlot::where('lobby_id', $this->activeLobbyId)->count();
            if ($remaining === 0) {
                Lobby::find($this->activeLobbyId)?->delete();
            }

            $this->activeLobbyId = null;

            return redirect()->route('arena.index');
        }
    }

    public function render()
    {
        $publicLobbies = Lobby::with(['district', 'sport'])
            ->withCount('slots')
            ->where('status', 'searching')
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.arena.arena', [
            'districts' => District::all(),
            'sports' => Sport::all(),
            'publicLobbies' => $publicLobbies,
        ]);
    }
}
