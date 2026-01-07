<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reserva;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EditReservaForm extends Component
{
    public Reserva $reserva;
    
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

    // 🔒 ESTADOS PERMITIDOS (Para visualización general)
    protected $estadosPermitidos = ['pending', 'advance', 'advance_paid', 'confirmed', 'paid', 'fully_paid'];

    public function mount(Reserva $reserva)
    {
        $this->reserva = $reserva;

        // 1. 🔒 BLOQUEO INICIAL: Si entra por URL directa
        if (!in_array($this->reserva->status, $this->estadosPermitidos)) {
            return redirect()->to(route('reservas.user.index')); 
        }

        // 2. Validación de propiedad
        if ($this->reserva->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta reserva.');
        }

        // 3. Cargar datos
        $this->date = Carbon::parse($reserva->start_time)->format('Y-m-d');
        $this->time = Carbon::parse($reserva->start_time)->format('H:i');
        
        $start = Carbon::parse($reserva->start_time);
        $end = Carbon::parse($reserva->end_time);
        $this->duration = (int) $start->diffInHours($end); 

        $this->calculatePrice();
        $this->generateTimeSlots();
    }

    // 🟢 TIEMPO REAL: Se dispara cada 2 seg (configurado en la vista)
    public function checkReservaStatus()
    {
        // 1. Refrescamos modelo desde BD
        $this->reserva->refresh();

        // 2. 🛑 SEGURIDAD: Si el estado cambió, expulsamos vía JS
        if (!in_array($this->reserva->status, $this->estadosPermitidos)) {
            
            // Disparamos evento al navegador para forzar cambio de URL
            $this->dispatch('force-redirect', url: route('reservas.user.index'));
            return;
        }

        // 3. Si sigue permitido, actualizamos los slots visuales
        $this->generateTimeSlots();
    }

    public function refreshSlots()
    {
        $this->generateTimeSlots();
    }

    public function updatedDate()
    {
        $this->generateTimeSlots();
        $this->time = '';
        $this->duration = 1;
        $this->total_price = 0;
    }

    public function selectTimeSlot($clickedTime)
    {
        foreach ($this->timeSlots as $slot) {
            if ($slot['value'] === $clickedTime && ($slot['disabled'] ?? false)) {
                return;
            }
        }

        if (!$this->time) {
            $this->time = $clickedTime;
            $this->duration = 1;
            $this->calculatePrice();
            return;
        }

        $start = Carbon::parse($this->date . ' ' . $this->time);
        $clicked = Carbon::parse($this->date . ' ' . $clickedTime);
        $endOfSelection = $start->copy()->addHours($this->duration);

        if ($clicked->gte($start) && $clicked->lt($endOfSelection)) {
            if ($clicked->eq($start)) {
                if ($this->duration == 1) {
                    $this->time = ''; 
                    $this->total_price = 0;
                } else {
                    $this->time = $start->addHour()->format('H:i'); 
                    $this->duration--; 
                }
            } else {
                $newDuration = $start->diffInHours($clicked);
                $this->duration = $newDuration;
            }
            $this->calculatePrice();
            return;
        }

        if ($clicked->lt($start)) {
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

        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if ($this->duration > 0 && $this->reserva->cancha) {
            $this->total_price = $this->reserva->cancha->price_per_hour * $this->duration;
        } else {
            $this->total_price = 0;
        }
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];
        $cancha = $this->reserva->cancha; 

        $openTime = Carbon::parse($this->date . ' ' . $cancha->open_time);
        $closeTime = Carbon::parse($this->date . ' ' . $cancha->close_time);

        if ($closeTime->lte($openTime)) {
            $closeTime->addDay();
        }

        $occupied = Reserva::where('cancha_id', $cancha->id)
            ->where('id', '!=', $this->reserva->id) 
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

            foreach ($occupied as $res) {
                $resStart = Carbon::parse($res->start_time);
                $resEnd = Carbon::parse($res->end_time);
                
                if ($currentSlot->lt($resEnd) && $slotEnd->gt($resStart)) {
                    if ($res->status === 'pending') {
                        if ($res->created_at > now()->subMinutes(10)) {
                            $isPending = true; 
                            $isPast = false; 
                        } else {
                            $isOccupied = true; 
                        }
                    } else {
                        $isOccupied = true; 
                    }
                    break;
                }
            }

            $this->timeSlots[] = [
                'value' => $currentSlot->format('H:i'),
                'label' => $currentSlot->format('h:i A'),
                'disabled' => $isPast || $isOccupied || $isPending,
                'is_occupied' => $isOccupied,
                'is_pending' => $isPending,
                'is_my_booking' => false 
            ];

            $currentSlot->addHour();
        }
    }

    protected function isRangeAvailable($start, $end) {
        return !Reserva::where('cancha_id', $this->reserva->cancha_id)
            ->where('id', '!=', $this->reserva->id) 
            ->where('status', '!=', 'cancelled')
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

    public function update()
    {
        // 1. Refrescar el modelo desde la base de datos para asegurar el dato real
        $this->reserva->refresh();

        // 2. Válidación de Seguridad estricta
        if ($this->reserva->status !== 'pending') {
            // Emitir alerta de error
            session()->flash('error', 'No se puede actualizar. La reserva ya no está pendiente (Estado actual: ' . $this->reserva->status . ')');
            
            // Forzar redirección
            $this->dispatch('force-redirect', url: route('reservas.user.index'));
            
            return;
        }

        $this->validate();

        try {
            $startDateTime = Carbon::parse($this->date . ' ' . $this->time);
            $endDateTime = $startDateTime->copy()->addHours((int)$this->duration);

            if ($startDateTime->isToday() && now()->gt($startDateTime->copy()->addMinutes(20))) {
                throw ValidationException::withMessages(['time' => 'Horario caducado (tolerancia superada).']);
            } elseif ($startDateTime->isPast()) {
                 throw ValidationException::withMessages(['time' => 'No puedes reservar en el pasado.']);
            }

            if (!$this->isRangeAvailable($startDateTime, $endDateTime)) {
                throw ValidationException::withMessages(['time' => 'El rango seleccionado ahora está ocupado.']);
            }

            $this->reserva->update([
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'total_price' => $this->total_price,
            ]);

            session()->flash('success', '¡Reserva actualizada con éxito!');
            return redirect()->route('reservas.user.index');

        } catch (ValidationException $e) {
            $this->addError('time', $e->getMessage()); 
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-reserva-form');
    }
}