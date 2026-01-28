<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Livewire\CanchaReservaForm.php
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
    public Cancha $cancha;
    public ?Reserva $reservaEdicion = null;
    
    public bool $isTournamentMode = false;

    // 🟢 NUEVA PROPIEDAD para guardar múltiples horas en modo torneo
    public $selectedSlots = []; 

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

    public function mount(Cancha $cancha, Reserva $reserva = null, $isTournamentMode = false)
    {
        $this->cancha = $cancha;
        $this->isTournamentMode = $isTournamentMode;
        
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
        $this->generateTimeSlots();
    }

    public function selectTimeSlot($clickedTime)
    {
        // 1. Validar si el slot está bloqueado visualmente (Deshabilitado)
        foreach ($this->timeSlots as $slot) {
            if ($slot['value'] === $clickedTime && ($slot['disabled'] ?? false)) {
                // Si es mío y estoy editando, permito click. Si no, retorno.
                if(!($slot['is_my_booking'] ?? false)) {
                    return;
                }
            }
        }

        // 🟢 2. LÓGICA MODO TORNEO: Multiselección (Toggle)
        if ($this->isTournamentMode) {
            if (in_array($clickedTime, $this->selectedSlots)) {
                // Si ya está, lo quitamos (deseleccionar)
                $this->selectedSlots = array_diff($this->selectedSlots, [$clickedTime]);
            } else {
                // Si no está, lo agregamos
                $this->selectedSlots[] = $clickedTime;
            }
            
            // Recalculamos precio basado en cantidad de slots seleccionados
            $this->calculatePrice();
            return; // 🛑 Importante: Detenemos aquí para no ejecutar lógica normal
        }

        // 3. Lógica de selección normal (Usuario final - Rangos)
        if (!$this->time) {
            $this->time = $clickedTime;
            $this->duration = 1;
        } else {
            $start = Carbon::parse($this->date . ' ' . $this->time);
            $clicked = Carbon::parse($this->date . ' ' . $clickedTime);
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

        $this->dispatch('time-selected', [
            'date' => $this->date,
            'time' => $this->time,
            'duration' => $this->duration 
        ]);
    }

    public function resetSelection()
    {
        $this->time = '';
        $this->duration = 1;
        $this->total_price = 0;
        $this->selectedSlots = []; // Limpiamos selección múltiple

        $this->dispatch('time-selected', [
            'date' => $this->date,
            'time' => ''
        ]);
    }

    public function calculatePrice()
    {
        if (!isset($this->cancha)) return;

        // 🟢 Cálculo diferente si es Torneo
        if ($this->isTournamentMode) {
            $count = count($this->selectedSlots);
            $this->total_price = $this->cancha->price_per_hour * $count;
        } 
        // Cálculo Normal
        else {
            if ($this->duration > 0) {
                $this->total_price = $this->cancha->price_per_hour * $this->duration;
            } else {
                $this->total_price = 0;
            }
        }
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

        // Traemos reservas, ignorando canceladas
        $occupied = Reserva::where('cancha_id', $this->cancha->id)
            ->where('status', '!=', 'cancelled') 
            ->where(function ($query) use ($openTime, $closeTime) {
                $query->whereBetween('start_time', [$openTime, $closeTime])
                      ->orWhereBetween('end_time', [$openTime, $closeTime]);
            })
            ->get();

        $currentSlot = $openTime->copy();

        while ($currentSlot->lt($closeTime)) {
            $slotEnd = $currentSlot->copy()->addHour(); 
            if ($slotEnd->gt($closeTime)) break; 

            $isPast = false;
            if (Carbon::parse($this->date)->isToday()) {
                if (now()->gt($currentSlot->copy()->addMinutes(20))) {
                    $isPast = true;
                }
            } elseif (Carbon::parse($this->date)->isPast()) {
                $isPast = true;
            }

            $isOccupied = false;
            $isPending = false;
            $isMyBooking = false;
            $isTournament = false; 

            foreach ($occupied as $reserva) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                
                if ($currentSlot->lt($resEnd) && $slotEnd->gt($resStart)) {
                    
                    if ($this->reservaEdicion && $this->reservaEdicion->id === $reserva->id) {
                        continue; 
                    }

                    if ($reserva->payment_type === 'tournament') {
                        $isTournament = true;
                        $isOccupied = true;
                    }

                    $estadosConfirmados = ['confirmed', 'paid', 'fully_paid', 'advance', 'advance_paid'];
                    
                    if ($reserva->user_id == Auth::id()) {
                        if (in_array($reserva->status, $estadosConfirmados)) {
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
                'label' => $currentSlot->format('h:i A'),
                'disabled' => ($isPast || $isOccupied || $isPending) && !$isMyBooking, 
                'is_occupied' => $isOccupied,
                'is_pending' => $isPending,
                'is_my_booking' => $isMyBooking,
                'is_tournament' => $isTournament, 
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
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '>=', $start)
                      ->where('start_time', '<', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('end_time', '>', $start)
                      ->where('end_time', '<=', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $start)
                      ->where('end_time', '>', $end);
                });
            })
            ->exists();
    }

    public function save()
    {
        // 🟢 LÓGICA DE GUARDADO PARA TORNEO (Múltiples Bloques)
        if ($this->isTournamentMode) {
            
            if (empty($this->selectedSlots)) {
                $this->addError('selectedSlots', 'Debes seleccionar al menos una hora.');
                return;
            }

            $successCount = 0;

            foreach ($this->selectedSlots as $slotTime) {
                $start = Carbon::parse($this->date . ' ' . $slotTime);
                $end = $start->copy()->addHour(); // Asumimos bloques de 1 hora individuales

                // Verificar disponibilidad de ESTE bloque específico
                if ($this->isRangeAvailable($start, $end)) {
                    Reserva::create([
                        'cancha_id'    => $this->cancha->id,
                        'user_id'      => Auth::id(),
                        'start_time'   => $start,
                        'end_time'     => $end,
                        'total_price'  => $this->cancha->price_per_hour,
                        'payment_type' => 'tournament', // Flag importante
                        'status'       => 'confirmed', // Bloqueo directo
                        'details'      => 'Bloqueo por Torneo'
                    ]);
                    $successCount++;
                }
            }

            // Reset y feedback
            $this->reset(['selectedSlots', 'total_price']);
            $this->generateTimeSlots(); // Refrescar visualmente
            
            if ($successCount > 0) {
                session()->flash('success', "Se han bloqueado {$successCount} horas para el torneo.");
            } else {
                session()->flash('error', 'No se pudieron bloquear las horas seleccionadas (ya estaban ocupadas).');
            }
            return; // 🛑 Salimos para no ejecutar el guardado normal
        }


        // 🔵 LÓGICA DE GUARDADO NORMAL (Usuario Standard)
        $this->validate();

        try {
            $startDateTime = Carbon::parse($this->date . ' ' . $this->time);
            $endDateTime = $startDateTime->copy()->addHours((int)$this->duration);

            if ($startDateTime->isToday() && now()->gt($startDateTime->copy()->addMinutes(20))) {
                throw ValidationException::withMessages(['time' => 'Tiempo de tolerancia expirado.']);
            } elseif ($startDateTime->isPast()) {
                 throw ValidationException::withMessages(['time' => 'Fecha pasada.']);
            }

            if (!$this->isRangeAvailable($startDateTime, $endDateTime)) {
                $this->generateTimeSlots(); 
                throw ValidationException::withMessages([
                    'time' => '¡Lo sentimos! Alguien acaba de tomar este horario.'
                ]);
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
                return redirect()->to('http://localhost:8000/mis-reservas');
            }
        }
        $this->generateTimeSlots();
    }

    public function render()
    {
        return view('livewire.cancha-reserva-form');
    }
}