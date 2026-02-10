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

    // Escuchamos eventos para actualizar la vista en tiempo real
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Lobby $lobby)
    {
        $this->lobby = $lobby;
        
        // 1. CORREGIR CAPACIDAD
        $this->fixLobbyCapacity(); 

        // 2. LIMPIEZA DE DUPLICADOS (Seguridad)
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

        // Revisar estado inicial al entrar
        $this->checkLobbyStatus(); 

        // Si está buscando, extendemos la vida útil del lobby
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
        if ($this->lobby->max_slots !== $baseSlots) {
            $this->lobby->update(['max_slots' => $baseSlots]);
            $this->lobby->refresh();
        }
    }

    public function increaseCapacity()
    {
        $sportName = strtolower($this->lobby->sport->name ?? '');
        if (str_contains($sportName, 'futbol') || str_contains($sportName, 'fútbol') || str_contains($sportName, 'tenis')) return;

        $this->lobby->increment('max_slots', 2);
        $this->lobby->refresh();
        $this->checkLobbyStatus(); 
    }

    public function checkLobbyStatus()
    {
        $this->lobby->refresh();
        
        $playerCount = $this->lobby->slots()->count();
        $maxPlayers = $this->lobby->max_slots;

        // CASO 1: SE LLENÓ LA SALA
        if ($playerCount >= $maxPlayers && $this->lobby->status === 'searching') {
            
            $updated = $this->lobby->update(['status' => 'confirming', 'expires_at' => now()->addHours(2)]);
            
            if ($updated) {
                try {
                    $users = $this->lobby->slots->map(fn($s) => $s->user);
                    Notification::send($users, new LobbyFullNotification($this->lobby));
                } catch (\Exception $e) {}
            }

        } 
        // CASO 2: SE VACIÓ
        elseif ($playerCount < $maxPlayers && $this->lobby->status !== 'searching') {
            $this->lobby->update(['status' => 'searching', 'expires_at' => now()->addHours(48)]);
        }
    }

    public function toggleReady()
    {
        if (!$this->userSlot) return;

        $newValue = $this->userSlot->confirmed_at ? null : now();
        $this->userSlot->update(['confirmed_at' => $newValue]);
        
        $this->checkStartGame();
    }

    public function checkStartGame()
    {
        $this->lobby->refresh();
        
        $maxPlayers = $this->lobby->max_slots;
        $occupiedSlots = $this->lobby->slots()->count();
        $confirmedCount = $this->lobby->slots()->whereNotNull('confirmed_at')->count();

        // CONDICIÓN ESTRICTA: Sala llena Y Todos Confirmados
        if ($occupiedSlots === $maxPlayers && $confirmedCount === $maxPlayers) {
            
            if ($this->lobby->status !== 'ready_to_play') {
                $this->lobby->update(['status' => 'ready_to_play']);
                
                $captains = $this->lobby->slots->where('is_captain', true)->map(fn($s) => $s->user);
                try {
                    // Notification::send($captains, new ReadyForReservationNotification($this->lobby));
                } catch (\Exception $e) {}
            }

        } else {
            // Si alguien quitó el check, volvemos a 'confirming'
            if ($this->lobby->status === 'ready_to_play') {
                $this->lobby->update(['status' => 'confirming']);
            }
        }
    }

    public function loadEntertainment()
    {
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
        
        if ($this->lobby->slots()->count() === 0) {
            $this->lobby->delete();
        } else {
            $this->checkLobbyStatus();
            $this->checkStartGame();
        }

        return redirect()->route('arena.index');
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '') return;
        
        Message::create([
            'lobby_id' => $this->lobby->id, 
            'sender_id' => Auth::id(), // 🟢 CORREGIDO: Antes decía 'user_id', debe ser 'sender_id'
            'content' => $this->newMessage, 
            'type' => 'general'
        ]);
        
        $this->newMessage = '';
    }

    public function render()
    {
        $this->lobby->load(['messages.sender', 'slots.user']);
        
        return view('livewire.arena.lobby-room', [
            'lobbyMessages' => $this->lobby->messages()->latest()->take(50)->get()->reverse(),
            'playerCount' => $this->lobby->slots->count(),
            'maxPlayers' => $this->lobby->max_slots, 
            'confirmedCount' => $this->lobby->slots->whereNotNull('confirmed_at')->count()
        ]);
    }
}