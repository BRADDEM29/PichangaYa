<?php

namespace App\Livewire\Arena;

use Livewire\Component;
use App\Models\Lobby;
use App\Models\LobbySlot;
use App\Models\Cancha;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LobbyFullNotification;
use App\Notifications\ReadyForReservationNotification;

class LobbyRoom extends Component
{
    public $lobby;
    public $userSlot;
    
    // UI
    public $chatTab = 'general';
    public $newMessage = '';
    public $carouselItems;

    public function mount(Lobby $lobby)
    {
        $this->lobby = $lobby;
        $this->userSlot = $this->lobby->slots()->where('user_id', Auth::id())->first();
        
        if (!$this->userSlot) {
            return redirect()->route('arena.index');
        }

        // 1. CORRECCIÓN INICIAL
        $this->fixLobbyCapacity();
        $this->checkLobbyStatus(); // Forzar revisión al entrar

        if ($this->lobby->status === 'searching') {
            $this->lobby->update(['expires_at' => now()->addHours(48)]);
        }

        $this->loadEntertainment();
    }

    /**
     * 🛡️ OBTENER CAPACIDAD SEGURA
     * Evita que el valor sea null o 0.
     */
    public function getSafeMaxSlots()
    {
        // Si la BD dice 0 o null, asumimos 14 por seguridad
        if (!$this->lobby->max_slots || $this->lobby->max_slots < 2) {
            return 14; 
        }
        return $this->lobby->max_slots;
    }

    public function fixLobbyCapacity()
    {
        $this->lobby->refresh();
        $sportName = strtolower($this->lobby->sport->name ?? '');
        
        // Valores Base
        $baseSlots = 14; 
        if (str_contains($sportName, 'tenis') || str_contains($sportName, 'padel') || str_contains($sportName, '1vs1')) $baseSlots = 2;
        elseif (str_contains($sportName, 'futbol 5') || str_contains($sportName, 'fútbol 5')) $baseSlots = 10;
        elseif (str_contains($sportName, 'futbol 7') || str_contains($sportName, 'fútbol 7')) $baseSlots = 14;
        elseif (str_contains($sportName, 'voley') || str_contains($sportName, 'vóley')) $baseSlots = 12;
        elseif (str_contains($sportName, 'basket') || str_contains($sportName, 'básquet')) $baseSlots = 10;

        $isFutbol = str_contains($sportName, 'futbol') || str_contains($sportName, 'fútbol');

        // Si hay error en BD (valor < 2) o es fútbol con valor incorrecto, CORREGIR
        if ($this->lobby->max_slots < 2 || ($isFutbol && $this->lobby->max_slots !== $baseSlots)) {
            $this->lobby->update(['max_slots' => $baseSlots]);
            $this->lobby->refresh();
        }
    }

    public function increaseCapacity()
    {
        $sportName = strtolower($this->lobby->sport->name ?? '');
        if (str_contains($sportName, 'futbol') || str_contains($sportName, 'fútbol')) return;

        $this->lobby->increment('max_slots', 2);
        $this->lobby->refresh();
        $this->checkLobbyStatus(); // Revisar estado tras cambio
    }

    /**
     * 🟢 REVISIÓN CONSTANTE DE ESTADO
     */
    public function checkLobbyStatus()
    {
        $this->lobby->refresh();
        $playerCount = $this->lobby->slots()->count();
        $maxPlayers = $this->getSafeMaxSlots(); // Usamos el valor seguro

        // CASO 1: SE LLENÓ (Searching -> Confirming)
        if ($playerCount >= $maxPlayers && $this->lobby->status === 'searching') {
            $this->lobby->update(['status' => 'confirming', 'expires_at' => now()->addHours(2)]);
            
            try {
                $users = $this->lobby->slots->map(fn($s) => $s->user);
                Notification::send($users, new LobbyFullNotification($this->lobby));
            } catch (\Exception $e) {}
        } 
        // CASO 2: NO ESTÁ LLENO (Cualquier estado -> Searching)
        // Esto arregla tu bug: Si hay menos jugadores que el máximo, ¡FUERZA SEARCHING!
        elseif ($playerCount < $maxPlayers && $this->lobby->status !== 'searching') {
            $this->lobby->update(['status' => 'searching', 'expires_at' => now()->addHours(48)]);
        }
    }

    public function checkStartGame()
    {
        $maxPlayers = $this->getSafeMaxSlots();
        $confirmedCount = $this->lobby->slots()->whereNotNull('confirmed_at')->count();

        if ($this->lobby->status === 'confirming' && $confirmedCount >= $maxPlayers) {
            $this->lobby->update(['status' => 'ready_to_play']);
            
            $captains = $this->lobby->slots->where('is_captain', true)->map(fn($s) => $s->user);
            try {
                Notification::send($captains, new ReadyForReservationNotification($this->lobby));
            } catch (\Exception $e) {}
        }
    }

    public function toggleReady()
    {
        if (!$this->userSlot) return;
        $newValue = $this->userSlot->confirmed_at ? null : now();
        $this->userSlot->update(['confirmed_at' => $newValue]);
        $this->lobby->refresh();
        $this->checkStartGame();
    }

    // --- CARGA DATOS ---
    public function loadEntertainment()
    {
        // Sin filtrar por sport_id aun para evitar error 500
        $this->carouselItems = Cancha::where('district_id', $this->lobby->district_id)
            ->where('is_active', true)
            ->with(['media', 'district'])
            ->inRandomOrder()->take(6)->get();
    }

    public function moveToTeam($targetTeam)
    {
        if (!in_array($targetTeam, ['A', 'B'])) return;
        $this->userSlot->refresh();
        if ($this->userSlot->team_side === $targetTeam) return;

        $maxPlayers = $this->getSafeMaxSlots();
        $maxPerTeam = intdiv($maxPlayers, 2);
        
        if ($this->lobby->slots()->where('team_side', $targetTeam)->count() >= $maxPerTeam) return;

        $this->userSlot->update(['team_side' => $targetTeam, 'is_captain' => false]);
        $this->lobby->refresh();
    }

    public function toggleCaptain()
    {
        $this->userSlot->refresh();
        if ($this->userSlot->is_captain) {
            $this->userSlot->update(['is_captain' => false]);
        } else {
            $exists = $this->lobby->slots()->where('team_side', $this->userSlot->team_side)->where('is_captain', true)->exists();
            if (!$exists) $this->userSlot->update(['is_captain' => true]);
        }
        $this->lobby->refresh();
    }

    public function exitLobby()
    {
        if ($this->userSlot) $this->userSlot->delete();
        if ($this->lobby->slots()->count() === 0) $this->lobby->delete();
        return redirect()->route('arena.index');
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '') return;
        Message::create(['lobby_id' => $this->lobby->id, 'user_id' => Auth::id(), 'content' => $this->newMessage, 'type' => 'general']);
        $this->newMessage = '';
    }

    public function render()
    {
        $this->checkLobbyStatus();
        $this->lobby->load(['messages.sender', 'slots.user']);
        
        // Pasamos maxPlayers calculado de forma segura
        $safeMax = $this->getSafeMaxSlots();

        return view('livewire.arena.lobby-room', [
            'lobbyMessages' => $this->lobby->messages()->latest()->take(50)->get()->reverse(),
            'playerCount' => $this->lobby->slots->count(),
            'maxPlayers' => $safeMax, // ¡IMPORTANTE! Usamos la variable segura aquí
            'confirmedCount' => $this->lobby->slots->whereNotNull('confirmed_at')->count()
        ]);
    }
}