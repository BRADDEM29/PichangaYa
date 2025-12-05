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
        'duration' => 'required|integer|min:1|max:4',
    ];

    public function mount(Cancha $cancha)
    {
        $this->cancha = $cancha;
        // Establecemos la fecha de hoy por defecto
        $this->date = Carbon::now()->toDateString();
        
        $this->generateTimeSlots();
        $this->calculatePrice();
    }

    public function updatedDate()
    {
        $this->generateTimeSlots();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['date', 'time', 'duration'])) {
            $this->calculatePrice();
        }
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];

        // 1. Obtener Hora de Apertura y Cierre para LA FECHA SELECCIONADA
        $openTime = Carbon::parse($this->date . ' ' . $this->cancha->open_time);
        $closeTime = Carbon::parse($this->date . ' ' . $this->cancha->close_time);

        // Corrección: Si cierra pasada la medianoche (ej: abre 14:00, cierra 02:00)
        if ($closeTime->lte($openTime)) {
            $closeTime->addDay();
        }

        // 2. Obtener Reservas Existentes en ese rango
        $occupied = Reserva::where('cancha_id', $this->cancha->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function ($query) use ($openTime, $closeTime) {
                $query->whereBetween('start_time', [$openTime, $closeTime])
                      ->orWhereBetween('end_time', [$openTime, $closeTime]);
            })
            ->get();

        // 3. Generar Slots cada 30 minutos
        $currentSlot = $openTime->copy();

        // Bucle: Mientras el slot actual sea menor al cierre
        while ($currentSlot->lt($closeTime)) {
            
            // Calculamos el fin de este slot (si dura 30 mins el bloque mínimo)
            // Ojo: Esto es solo para verificar si cabe en el horario de atención
            $slotEnd = $currentSlot->copy()->addMinutes(30);
            
            if ($slotEnd->gt($closeTime)) {
                break; // Ya no cabe otro turno
            }

            // --- LÓGICA DE "PASADO" CON TOLERANCIA ---
            // Si son las 9:10, todavía permitimos reservar el turno de las 9:00
            // Damos 15 minutos de gracia.
            $isPast = false;
            // "Si AHORA es mayor que (Hora del Turno + 15 min), entonces ya pasó"
            if (Carbon::now()->gt($currentSlot->copy()->addMinutes(15))) {
                // Solo marcamos como pasado si la fecha seleccionada es hoy
                if (Carbon::parse($this->date)->isToday()) {
                    $isPast = true;
                } elseif (Carbon::parse($this->date)->isPast()) {
                    // Si seleccionó ayer, todo está pasado
                    $isPast = true;
                }
            }

            // --- LÓGICA DE OCUPADO ---
            $isOccupied = false;
            foreach ($occupied as $reserva) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);

                // Verificamos choque de horarios con precisión
                // ¿El turno actual choca con alguna reserva?
                // (Start < ResEnd) y (End > ResStart)
                if ($currentSlot->lt($resEnd) && $slotEnd->gt($resStart)) {
                    $isOccupied = true;
                    break;
                }
            }

            $this->timeSlots[] = [
                'value' => $currentSlot->format('H:i'),
                'label' => $currentSlot->format('h:i A'), // 08:00 AM, 08:30 AM
                'disabled' => $isPast || $isOccupied,
                'is_occupied' => $isOccupied
            ];

            // Avanzamos 30 minutos
            $currentSlot->addMinutes(30);
        }
    }

    public function calculatePrice()
    {
        if ($this->duration > 0) {
            $this->total_price = $this->cancha->price_per_hour * $this->duration;
        } else {
            $this->total_price = 0;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // 1. Crear objetos Carbon con fecha + hora
            $startDateTime = Carbon::parse($this->date . ' ' . $this->time);
            
            // 2. Calcular hora fin (Duration es en horas enteras)
            $endDateTime = $startDateTime->copy()->addHours((int)$this->duration);

            // 3. Validar cierre
            // Re-calculamos el cierre considerando si es el día siguiente
            $openTime = Carbon::parse($this->date . ' ' . $this->cancha->open_time);
            $closeTime = Carbon::parse($this->date . ' ' . $this->cancha->close_time);
            if ($closeTime->lte($openTime)) {
                $closeTime->addDay();
            }

            if ($endDateTime->gt($closeTime)) {
                throw ValidationException::withMessages([
                    'duration' => 'La duración excede el horario de cierre (' . $this->cancha->close_time . ').'
                ]);
            }

            // 4. Validar Disponibilidad
            if (!$this->isRangeAvailable($startDateTime, $endDateTime)) {
                throw ValidationException::withMessages([
                    'time' => 'El horario seleccionado choca con otra reserva existente.'
                ]);
            }

            // 5. Preparar Request
            $fakeRequest = new Request();
            $fakeRequest->setMethod('POST');
            $fakeRequest->replace([
                'cancha_id'   => $this->cancha->id,
                'start_time'  => $startDateTime->toDateTimeString(),
                'end_time'    => $endDateTime->toDateTimeString(),
                'total_price' => $this->total_price,
                'status'      => 'pending',
            ]);

            // 6. Guardar
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
                    // Caso: Reserva envuelve completamente al rango nuevo
                    $q->where('start_time', '<', $start)
                      ->where('end_time', '>', $end);
                });
            })
            ->exists();
    }

    public function render()
    {
        return view('livewire.cancha-reserva-form');
    }
}