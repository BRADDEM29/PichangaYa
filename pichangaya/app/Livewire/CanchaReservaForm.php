<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Livewire\CanchaReservaForm.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Cancha;
use App\Models\Reserva;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CanchaReservaForm extends Component
{
    public Cancha $cancha;
    
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

    public function mount(Cancha $cancha)
    {
        $this->cancha = $cancha;
        $this->date = Carbon::now()->toDateString();
        $this->generateTimeSlots();
        $this->calculatePrice();
    }

    public function updatedDate()
    {
        $this->generateTimeSlots();
        $this->resetSelection();
    }

    public function selectTimeSlot($clickedTime)
    {
        // 1. Validar que no estemos seleccionando slots deshabilitados (Pasados u Ocupados)
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

        // Lógica de recorte (si clickea dentro de su selección actual)
        if ($clicked->gte($start) && $clicked->lt($endOfSelection)) {
            if ($clicked->eq($start)) {
                if ($this->duration == 1) {
                    $this->resetSelection(); 
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

        // Lógica de nueva selección o extensión
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

    public function resetSelection()
    {
        $this->time = '';
        $this->duration = 1;
        $this->total_price = 0;
    }

    public function calculatePrice()
    {
        if ($this->duration > 0) {
            $this->total_price = $this->cancha->price_per_hour * $this->duration;
        } else {
            $this->total_price = 0;
        }
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];

        $openTime = Carbon::parse($this->date . ' ' . $this->cancha->open_time);
        $closeTime = Carbon::parse($this->date . ' ' . $this->cancha->close_time);

        if ($closeTime->lte($openTime)) {
            $closeTime->addDay();
        }

        // Traer reservas activas (No canceladas)
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

            // 🟢 LÓGICA DE TOLERANCIA DE 20 MINUTOS
            $isPast = false;
            
            // Solo verificamos si es "pasado" si estamos en el día de hoy
            // Si la fecha seleccionada es hoy...
            if (Carbon::parse($this->date)->isToday()) {
                // ...y la hora actual es MAYOR a la hora del slot + 20 minutos
                // Ejemplo: Slot 9:00. Tolerancia hasta 9:20. Si son 9:21 -> Bloqueado.
                if (now()->gt($currentSlot->copy()->addMinutes(20))) {
                    $isPast = true;
                }
            } 
            // Si la fecha es ayer o antes, todo es pasado
            elseif (Carbon::parse($this->date)->isPast()) {
                $isPast = true;
            }

            $isOccupied = false;
            $isPending = false; // 🟡 Bandera amarilla

            foreach ($occupied as $reserva) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                
                if ($currentSlot->lt($resEnd) && $slotEnd->gt($resStart)) {
                    
                    // Si existe una reserva (PENDIENTE), la mostramos aunque sea hora pasada
                    // porque el cliente la "ganó" a tiempo.
                    if ($reserva->status === 'pending') {
                        if ($reserva->created_at > now()->subMinutes(10)) {
                            $isPending = true; // Amarillo
                            $isPast = false; // Forzamos false para que se vea el amarillo y no el gris de "pasado"
                        } else {
                            $isOccupied = true; // Gris (Vencido pero no borrado aun)
                        }
                    } else {
                        $isOccupied = true; // Gris (Confirmado)
                    }
                    break;
                }
            }

            $this->timeSlots[] = [
                'value' => $currentSlot->format('H:i'),
                'label' => $currentSlot->format('h:i A'),
                // Se deshabilita si: Pasó la tolerancia, o está ocupado, o está pendiente de otro
                'disabled' => $isPast || $isOccupied || $isPending,
                'is_occupied' => $isOccupied,
                'is_pending' => $isPending,
            ];

            $currentSlot->addHour();
        }
    }

    protected function isRangeAvailable($start, $end) {
        return !Reserva::where('cancha_id', $this->cancha->id)
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

    public function save()
    {
        $this->validate();

        try {
            $startDateTime = Carbon::parse($this->date . ' ' . $this->time);
            $endDateTime = $startDateTime->copy()->addHours((int)$this->duration);

            // 🟢 VALIDACIÓN DE SEGURIDAD BACKEND (TOLERANCIA 20 MIN)
            // Esto evita que alguien modifique el HTML disabled y mande la petición igual
            if ($startDateTime->isToday()) {
                if (now()->gt($startDateTime->copy()->addMinutes(20))) {
                    throw ValidationException::withMessages([
                        'time' => 'El tiempo de tolerancia (20 min) para este horario ha expirado.'
                    ]);
                }
            } elseif ($startDateTime->isPast()) {
                 throw ValidationException::withMessages([
                    'time' => 'No puedes reservar en una fecha pasada.'
                ]);
            }

            if (!$this->isRangeAvailable($startDateTime, $endDateTime)) {
                throw ValidationException::withMessages([
                    'time' => 'El rango seleccionado choca con otra reserva.'
                ]);
            }

            $fakeRequest = new Request();
            $fakeRequest->setMethod('POST');
            $fakeRequest->replace([
                'cancha_id'   => $this->cancha->id,
                'start_time'  => $startDateTime->toDateTimeString(),
                'end_time'    => $endDateTime->toDateTimeString(),
                'total_price' => $this->total_price,
                'status'      => 'pending',
            ]);

            $controller = new \App\Http\Controllers\ReservaController();
            $controller->store($fakeRequest);

            session()->flash('success', '¡Reserva realizada con éxito!');
            return redirect()->route('reservas.user.index');

        } catch (ValidationException $e) {
            $this->addError('time', $e->getMessage()); 
        } catch (\Exception $e) {
            session()->flash('error', 'Error técnico: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.cancha-reserva-form');
    }
}