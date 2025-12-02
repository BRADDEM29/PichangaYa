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
        $this->date = Carbon::today()->toDateString();
        
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

        // 🟢 CAMBIO: Obtener horas dinámicas de la base de datos
        $startHour = Carbon::parse($this->cancha->open_time)->hour;
        $endHour = Carbon::parse($this->cancha->close_time)->hour;

        // Validamos si la cancha cierra a las 00:00 o más tarde en el mismo día lógico
        if ($endHour <= $startHour) {
            $endHour = 23; // Fallback simple para evitar loops infinitos si la data está mal
        }
        
        // Consultar reservas existentes
        $occupied = Reserva::where('cancha_id', $this->cancha->id)
            ->whereDate('start_time', $this->date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $time = Carbon::createFromTime($hour, 0);
            $timeStr = $time->format('H:i:s');
            
            // Verificar si ya pasó la hora (si es hoy)
            $isPast = false;
            if ($this->date === Carbon::today()->toDateString()) {
                if ($time->lt(Carbon::now())) {
                    $isPast = true;
                }
            }

            // Verificar si está ocupado
            $isOccupied = false;
            foreach ($occupied as $reserva) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                
                // Si la hora del slot cae dentro de una reserva
                if ($time->gte($resStart) && $time->lt($resEnd)) {
                    $isOccupied = true;
                    break;
                }
            }

            $this->timeSlots[] = [
                'value' => $time->format('H:i'),
                'label' => $time->format('h:i A'),
                'disabled' => $isPast || $isOccupied,
                'is_occupied' => $isOccupied
            ];
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
            // 1. Convertir fecha y hora separadas en objetos Carbon
            $startDateTime = Carbon::parse($this->date . ' ' . $this->time);
            
            // CORRECCIÓN ANTERIOR: Forzamos (int) para que no falle Carbon
            $endDateTime = $startDateTime->copy()->addHours((int) $this->duration);

            // 2. Validar cierre de la cancha
            $closeTime = Carbon::parse($this->date . ' ' . $this->cancha->close_time);
            if ($endDateTime->gt($closeTime)) {
                throw ValidationException::withMessages([
                    'duration' => 'La duración excede el horario de cierre (' . $this->cancha->close_time . ').'
                ]);
            }

            // 3. Validar disponibilidad (Choques)
            if (!$this->isRangeAvailable($startDateTime, $endDateTime)) {
                throw ValidationException::withMessages([
                    'time' => 'El horario seleccionado ya está ocupado.'
                ]);
            }

            // 4. PREPARAR EL PAQUETE PARA EL CONTROLADOR (Aquí estaba el error)
            // Creamos un Request falso pero con los datos YA CALCULADOS que pide la base de datos.
            $fakeRequest = new Request();
            $fakeRequest->setMethod('POST');
            $fakeRequest->replace([
                'cancha_id'   => $this->cancha->id,
                // Enviamos los campos exactos que pide el validador del compañero
                'start_time'  => $startDateTime->toDateTimeString(), // Ej: "2025-12-03 10:00:00"
                'end_time'    => $endDateTime->toDateTimeString(),   // Ej: "2025-12-03 12:00:00"
                'total_price' => $this->total_price,                 // Precio calculado
                'status'      => 'pending',                          // Estado inicial
            ]);

            // 5. Llamar al controlador
            $controller = new \App\Http\Controllers\ReservaController();
            $controller->store($fakeRequest);

            // 6. Éxito y Redirección
            session()->flash('success', '¡Reserva realizada con éxito!');
            return redirect()->route('reservas.user.index');

        } catch (ValidationException $e) {
            // Si falla validación nuestra o del controlador
            $this->addError('time', $e->getMessage()); 
        } catch (\Exception $e) {
            // Errores generales
            session()->flash('error', 'Error técnico: ' . $e->getMessage());
        }
    }

    protected function isRangeAvailable($start, $end) {
        return !Reserva::where('cancha_id', $this->cancha->id)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '>=', $start)
                      ->where('start_time', '<', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('end_time', '>', $start)
                      ->where('end_time', '<=', $end);
                });
            })
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();
    }

    public function render()
    {
        return view('livewire.cancha-reserva-form');
    }
}