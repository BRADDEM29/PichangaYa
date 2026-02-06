<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Http\Request;
use App\Models\Cancha;
use App\Models\Reserva;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CanchaReservaForm extends Component
{
    public ?Cancha $cancha = null; 
    public ?Reserva $reservaEdicion = null;
    
    // 🟢 NUEVO: Propiedad para almacenar el ID de la reserva a ignorar (Torneos)
    public $ignoringReservaId = null; 

    // El modo torneo sigue existiendo para la SELECCIÓN (arrastrar),
    // pero ya no afecta visualmente a las reservas pasadas.
    public bool $isTournamentMode = false;

    public $date = '';
    public $time = ''; 
    public $duration = 1;
    public $total_price = 0.00;
    
    public $timeSlots = []; 

    protected $rules = [
        'date' => 'required|date|after_or_equal:today',
        'time' => 'required', 
        'duration' => 'required|integer|min:1|max:24', 
    ];

    #[On('updateCancha')]
    public function updateCancha($id)
    {
        $nuevaCancha = Cancha::find($id);
        if ($nuevaCancha) {
            $this->cancha = $nuevaCancha;
            $this->resetSelection(); 
            $this->generateTimeSlots();
            $this->calculatePrice();
        }
    }

    // 🟢 ACTUALIZADO: Recibe $ignoringReservaId
    public function mount($cancha = null, Reserva $reserva = null, $isTournamentMode = false, $ignoringReservaId = null)
    {
        if ($cancha) {
            $this->cancha = $cancha;
        } elseif (!$this->cancha) {
            $this->cancha = Cancha::first();
        }

        $this->isTournamentMode = $isTournamentMode;
        $this->ignoringReservaId = $ignoringReservaId; // Guardamos el ID a ignorar
        
        if ($reserva && $reserva->exists) {
            $this->reservaEdicion = $reserva;
            $this->date = Carbon::parse($reserva->start_time)->toDateString();
            $this->time = Carbon::parse($reserva->start_time)->format('H:i');
            $this->duration = Carbon::parse($reserva->start_time)->diffInHours($reserva->end_time);
        } else {
            $this->date = Carbon::now()->toDateString();
        }

        $this->generateTimeSlots();
        $this->calculatePrice();
    }

    public function updatedDate()
    {
        $this->generateTimeSlots();
        $this->resetSelection();
    }

    public function refreshSlots()
    {
        if($this->cancha) {
            $this->generateTimeSlots();
        }
    }

    public function selectTimeSlot($clickedTime)
    {
        // Validar click en casillas
        foreach ($this->timeSlots as $slot) {
            if ($slot['value'] === $clickedTime && ($slot['disabled'] ?? false)) {
                if(!($slot['is_my_booking'] ?? false)) return;
            }
        }

        if (!$this->time) {
            $this->time = $clickedTime;
            $this->duration = 1;
        } else {
            $start = Carbon::parse($this->date . ' ' . $this->time);
            $clicked = Carbon::parse($this->date . ' ' . $clickedTime);
            
            if ($clicked->lt(Carbon::parse($this->date . ' ' . $this->cancha->open_time))) {
                $clicked->addDay();
            }

            $endOfSelection = $start->copy()->addHours($this->duration);

            if ($clicked->gte($start) && $clicked->lt($endOfSelection)) {
                if ($clicked->eq($start)) {
                    if ($this->duration == 1) {
                        $this->resetSelection(); 
                        return;
                    } else {
                        $this->time = $start->addHour()->format('H:i'); 
                        $this->duration--; 
                    }
                } else {
                    $this->duration = $start->diffInHours($clicked);
                }
            } elseif ($clicked->lt($start)) {
                $this->time = $clickedTime;
                $this->duration = 1;
            } else {
                $potentialDuration = $start->diffInHours($clicked) + 1;
                $potentialEnd = $start->copy()->addHours($potentialDuration);

                if ($this->isRangeAvailable($start, $potentialEnd)) {
                    $this->duration = $potentialDuration;
                } else {
                    $this->time = $clickedTime;
                    $this->duration = 1;
                }
            }
        }

        $this->calculatePrice();
        
        if ($this->isTournamentMode && $this->time) {
            $startDisplay = Carbon::createFromFormat('H:i', $this->time)->format('h:i A');
            $this->dispatch('tournament-selection-updated', [
                'start_date' => $this->date . ' ' . $this->time,
                'duration'   => $this->duration,
                'count'      => $this->duration,
                'first_slot' => $startDisplay
            ]);
        }

        $this->dispatch('time-selected', ['date' => $this->date, 'time' => $this->time]);
    }

    public function resetSelection()
    {
        $this->time = '';
        $this->duration = 1;
        $this->total_price = 0;
        if ($this->isTournamentMode) {
            $this->dispatch('tournament-selection-cleared');
        }
    }

    public function calculatePrice()
    {
        if (!isset($this->cancha)) return;
        $this->total_price = ($this->duration > 0) ? ($this->cancha->price_per_hour * $this->duration) : 0;
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];
        if (!isset($this->cancha)) return;

        $openTime = Carbon::parse($this->date . ' ' . $this->cancha->open_time);
        $closeTime = Carbon::parse($this->date . ' ' . $this->cancha->close_time);

        if ($closeTime->lte($openTime)) {
            $closeTime->addDay();
        }

        $occupied = Reserva::where('cancha_id', $this->cancha->id)
            ->where('status', '!=', 'cancelled')
            // 🟢 ACTUALIZADO: Ignoramos la reserva del torneo actual para que salga libre
            ->when($this->ignoringReservaId, function($q) {
                $q->where('id', '!=', $this->ignoringReservaId);
            })
            ->where(function ($query) use ($openTime, $closeTime) {
                $query->whereBetween('start_time', [$openTime, $closeTime])
                      ->orWhereBetween('end_time', [$openTime, $closeTime]);
            })
            ->get();

        $currentSlot = $openTime->copy();

        while ($currentSlot->lt($closeTime)) {
            $slotEnd = $currentSlot->copy()->addHour(); 
            
            if ($slotEnd->gt($closeTime)) break; 

            // 🟢 REGLA DE 15 MINUTOS
            $isPast = false;
            if (Carbon::now()->toDateString() === $this->date) {
                if (now()->gt($currentSlot->copy()->addMinutes(15))) {
                     $isPast = true;
                }
            } elseif (Carbon::parse($this->date)->lt(Carbon::today())) {
                $isPast = true; 
            }

            $isOccupied = false;
            $isPending = false;
            $isMyBooking = false;

            foreach ($occupied as $reserva) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                
                if ($currentSlot->lt($resEnd) && $slotEnd->gt($resStart)) {
                    
                    if ($this->reservaEdicion && $this->reservaEdicion->id === $reserva->id) continue; 
                    
                    // Lógica estándar unificada
                    $estadosOk = ['confirmed', 'paid', 'fully_paid', 'advance', 'advance_paid'];
                    
                    if ($reserva->user_id == Auth::id()) {
                        if (in_array($reserva->status, $estadosOk)) {
                            $isMyBooking = true;
                            $isOccupied = true; 
                        } elseif ($reserva->status === 'pending') {
                            $isPending = true;
                            $isPast = false;
                        }
                    } else {
                        if ($reserva->status === 'pending') {
                            if ($reserva->created_at > now()->subMinutes(10)) {
                                $isPending = true;
                                $isPast = false; 
                            } 
                        } else {
                            $isOccupied = true; 
                        }
                    }
                }
            }

            $this->timeSlots[] = [
                'value' => $currentSlot->format('H:i'),
                'label_start' => $currentSlot->format('h:i A'),
                'label_end'   => $slotEnd->format('h:i A'),
                'disabled' => ($isPast || $isOccupied || $isPending) && !$isMyBooking, 
                'is_occupied' => $isOccupied,
                'is_pending' => $isPending,
                'is_my_booking' => $isMyBooking,
            ];

            $currentSlot->addHour();
        }
    }
    
    protected function isRangeAvailable($start, $end) {
        return !Reserva::where('cancha_id', $this->cancha->id)
            ->where('status', '!=', 'cancelled')
            ->when($this->reservaEdicion, function($q) {
                $q->where('id', '!=', $this->reservaEdicion->id);
            })
            // 🟢 ACTUALIZADO: Validamos también al verificar el rango
            ->when($this->ignoringReservaId, function($q) {
                $q->where('id', '!=', $this->ignoringReservaId);
            })
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })
            ->exists();
    }

    public function save()
    {
        $this->validate();

        try {
            $startDateTime = Carbon::parse($this->date . ' ' . $this->time);
             if (Carbon::parse($this->time)->lt(Carbon::parse($this->cancha->open_time))) {
                $startDateTime->addDay();
            }
            $endDateTime = $startDateTime->copy()->addHours((int)$this->duration);

            if (now()->gt($startDateTime->copy()->addMinutes(15))) {
                 throw ValidationException::withMessages(['time' => 'Tiempo de tolerancia expirado.']);
            }

            if (!$this->isRangeAvailable($startDateTime, $endDateTime)) {
                $this->generateTimeSlots(); 
                throw ValidationException::withMessages(['time' => '¡Lo sentimos! Alguien acaba de tomar este horario.']);
            }

            $fakeRequest = new Request();
            $fakeRequest->setMethod($this->reservaEdicion ? 'PUT' : 'POST');
            
            $data = [
                'cancha_id'   => $this->cancha->id,
                'start_time'  => $startDateTime->toDateTimeString(),
                'end_time'    => $endDateTime->toDateTimeString(),
                'total_price' => $this->total_price,
                'status'      => $this->reservaEdicion ? $this->reservaEdicion->status : 'pending',
            ];

            $fakeRequest->replace($data);
            $controller = new \App\Http\Controllers\ReservaController();
            
            if ($this->reservaEdicion) {
                $controller->update($fakeRequest, $this->reservaEdicion);
                session()->flash('success', '¡Reserva actualizada!');
            } else {
                $controller->store($fakeRequest);
                session()->flash('success', '¡Reserva creada!');
            }

            return redirect()->route('reservas.user.index');

        } catch (ValidationException $e) {
            $this->addError('time', $e->getMessage()); 
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function checkReservaStatus()
    {
        if ($this->reservaEdicion) {
            $this->reservaEdicion->refresh();
            $estadosPermitidos = ['confirmed', 'paid', 'fully_paid', 'advance', 'advance_paid', 'pending'];
            if (!in_array($this->reservaEdicion->status, $estadosPermitidos)) {
                return redirect()->to(route('reservas.user.index'));
            }
        }
        $this->generateTimeSlots();
    }

    public function render()
    {
        return view('livewire.cancha-reserva-form');
    }
}