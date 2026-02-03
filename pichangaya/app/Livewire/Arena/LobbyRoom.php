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
    
    public $chatTab = 'general';
    public $newMessage = '';
    public $carouselItems;

    public function mount(Lobby $lobby)
    {
        $this->lobby = $lobby;
        
        // 1. CORREGIR CAPACIDAD (CRÍTICO PARA ARREGLAR TENIS 1/14)
        // Hacemos esto antes de nada para que la vista renderice bien
        $this->fixLobbyCapacity(); 

        // 2. LIMPIEZA DE DUPLICADOS
        $mySlots = $this->lobby->slots()->where('user_id', Auth::id())->get();
        if ($mySlots->count() > 1) {
            $keep = $mySlots->first();
            LobbySlot::where('lobby_id', $this->lobby->id)
                ->where('user_id', Auth::id())
                ->where('id', '!=', $keep->id)
                ->delete();
            $this->userSlot = $keep;
            $this->lobby->refresh(); 
        } else {
            $this->userSlot = $mySlots->first();
        }
        
        if (!$this->userSlot) {
            return redirect()->route('arena.index');
        }

        // Revisar estado inicial
        $this->checkLobbyStatus(); 

        if ($this->lobby->status === 'searching') {
            $this->lobby->update(['expires_at' => now()->addHours(48)]);
        }

        $this->loadEntertainment();
    }

    // Calcula capacidad correcta según nombre del deporte
    public function calculateBaseSlots()
    {
        $sportName = strtolower($this->lobby->sport->name ?? '');
        
        if (str_contains($sportName, 'tenis') || str_contains($sportName, 'padel') || str_contains($sportName, '1vs1')) return 2;
        elseif (str_contains($sportName, 'futbol 5') || str_contains($sportName, 'fútbol 5')) return 10;
        elseif (str_contains($sportName, 'basket') || str_contains($sportName, 'básquet')) return 10;
        elseif (str_contains($sportName, 'voley') || str_contains($sportName, 'vóley')) return 12;
        
        return 14; 
    }

    // Fuerza la corrección en la BD si está mal
    public function fixLobbyCapacity()
    {
        $baseSlots = $this->calculateBaseSlots();
        
        // Si la BD dice un número diferente al que debería ser por deporte, lo corregimos
        if ($this->lobby->max_slots !== $baseSlots) {
            $this->lobby->update(['max_slots' => $baseSlots]);
            $this->lobby->refresh();
        }
    }

    public function increaseCapacity()
    {
        // No permitir aumentar en Tenis/Futbol, solo otros
        $sportName = strtolower($this->lobby->sport->name ?? '');
        if (str_contains($sportName, 'futbol') || str_contains($sportName, 'fútbol') || str_contains($sportName, 'tenis')) return;

        $this->lobby->increment('max_slots', 2);
        $this->lobby->refresh();
        $this->checkLobbyStatus(); 
    }

    /**
     * LÓGICA ANTI-DOBLE NOTIFICACIÓN
     */
    public function checkLobbyStatus()
    {
        // 1. Refrescar desde BD para tener el dato real
        $this->lobby->refresh();
        
        $playerCount = $this->lobby->slots()->count();
        $maxPlayers = $this->lobby->max_slots; // Usamos el valor directo de BD ya corregido

        // CASO 1: SE LLENÓ
        if ($playerCount >= $maxPlayers && $this->lobby->status === 'searching') {
            
            // 2. BLOQUEO: Cambiamos estado ANTES de notificar
            $updated = $this->lobby->update(['status' => 'confirming', 'expires_at' => now()->addHours(2)]);
            
            // 3. SOLO SI EL UPDATE FUE EXITOSO (True), enviamos notificación
            if ($updated) {
                try {
                    $users = $this->lobby->slots->map(fn($s) => $s->user);
                    Notification::send($users, new LobbyFullNotification($this->lobby, $playerCount, $maxPlayers));
                } catch (\Exception $e) {}
            }

        } 
        // CASO 2: SE VACIÓ
        elseif ($playerCount < $maxPlayers && $this->lobby->status !== 'searching') {
            $this->lobby->update(['status' => 'searching', 'expires_at' => now()->addHours(48)]);
        }
    }

    public function checkStartGame()
    {
        $this->lobby->refresh();
        
        // Si ya está listo, abortamos para no notificar doble
        if ($this->lobby->status === 'ready_to_play') return;

        $maxPlayers = $this->lobby->max_slots;
        $confirmedCount = $this->lobby->slots()->whereNotNull('confirmed_at')->count();

        if ($this->lobby->status === 'confirming' && $confirmedCount >= $maxPlayers) {
            
            // BLOQUEO: Cambiar estado primero
            $this->lobby->update(['status' => 'ready_to_play']);
            
            $captains = $this->lobby->slots->where('is_captain', true)->map(fn($s) => $s->user);
            try {
                Notification::send($captains, new ReadyForReservationNotification($this->lobby, $confirmedCount, $maxPlayers));
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

    public function loadEntertainment()
    {
        // Filtro seguro
        $this->carouselItems = Cancha::where('district_id', $this->lobby->district_id)
            ->where('is_active', true)
            ->with(['media', 'district'])
            ->inRandomOrder()->take(6)->get();
    }

    public function moveToTeam($targetTeam)
    {
        if (!in_array($targetTeam, ['A', 'B'])) return;
        $this->userSlot->refresh();
        if ($this->userSlot->confirmed_at) return;

        if ($this->userSlot->team_side === $targetTeam) return;

        $maxPlayers = $this->lobby->max_slots;
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
        $this->lobby->load(['messages.sender', 'slots.user']);
        
        return view('livewire.arena.lobby-room', [
            'lobbyMessages' => $this->lobby->messages()->latest()->take(50)->get()->reverse(),
            'playerCount' => $this->lobby->slots->count(),
            'maxPlayers' => $this->lobby->max_slots, // Usamos valor real de BD
            'confirmedCount' => $this->lobby->slots->whereNotNull('confirmed_at')->count()
        ]);
    }
}