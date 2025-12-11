<?php

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
        // 🟢 CORRECCIÓN 1: Aumentamos el máximo a 24 horas (o 15, lo que sea suficiente)
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

    // 🟢 LÓGICA DE SELECCIÓN Y RECORTE (TRIMMING)
    public function selectTimeSlot($clickedTime)
    {
        // 1. SI NO HAY NADA SELECCIONADO: MARCAR INICIO
        if (!$this->time) {
            $this->time = $clickedTime;
            $this->duration = 1;
            $this->calculatePrice();
            return;
        }

        $start = Carbon::parse($this->date . ' ' . $this->time);
        $clicked = Carbon::parse($this->date . ' ' . $clickedTime);
        $endOfSelection = $start->copy()->addHours($this->duration);

        // 2. LOGICA DE RECORTE (SI CLICKEO DENTRO DE LO VERDE)
        if ($clicked->gte($start) && $clicked->lt($endOfSelection)) {
            
            // CASO A: Clickeó el PRIMER cuadro (Inicio) -> Mover inicio
            if ($clicked->eq($start)) {
                if ($this->duration == 1) {
                    $this->resetSelection(); 
                } else {
                    $this->time = $start->addHour()->format('H:i'); 
                    $this->duration--; 
                }
            }
            // CASO B: Clickeó en MEDIO o FINAL -> Recortar cola
            else {
                $newDuration = $start->diffInHours($clicked);
                $this->duration = $newDuration;
            }

            $this->calculatePrice();
            return;
        }

        // 3. LOGICA DE EXTENSIÓN (SI CLICKEO FUERA)
        
        // Clickeó antes del inicio -> Nueva selección
        if ($clicked->lt($start)) {
            $this->time = $clickedTime;
            $this->duration = 1;
        } 
        // Clickeó después del final -> Intentar extender
        else {
            $potentialDuration = $start->diffInHours($clicked) + 1;
            $potentialEnd = $start->copy()->addHours($potentialDuration);

            // 🟢 CORRECCIÓN 2: Quitamos la restricción "&& $potentialDuration <= 6"
            // Ahora el único límite es que la cancha esté libre (isRangeAvailable).
            if ($this->isRangeAvailable($start, $potentialEnd)) {
                $this->duration = $potentialDuration;
            } else {
                // Si choca con otra reserva, reiniciamos en el click
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

        $occupied = Reserva::where('cancha_id', $this->cancha->id)
            ->whereIn('status', ['confirmed', 'pending'])
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
            if (Carbon::now()->gt($currentSlot->copy()->addMinutes(15))) {
                if (Carbon::parse($this->date)->isToday()) {
                    $isPast = true;
                } elseif (Carbon::parse($this->date)->isPast()) {
                    $isPast = true;
                }
            }

            $isOccupied = false;
            foreach ($occupied as $reserva) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                if ($currentSlot->lt($resEnd) && $slotEnd->gt($resStart)) {
                    $isOccupied = true;
                    break;
                }
            }

            $this->timeSlots[] = [
                'value' => $currentSlot->format('H:i'),
                'label' => $currentSlot->format('h:i A'),
                'disabled' => $isPast || $isOccupied,
                'is_occupied' => $isOccupied
            ];

            $currentSlot->addHour();
        }
    }

    protected function isRangeAvailable($start, $end) {
        return !Reserva::where('cancha_id', $this->cancha->id)
            ->whereIn('status', ['confirmed', 'pending'])
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