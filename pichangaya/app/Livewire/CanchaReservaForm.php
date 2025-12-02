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
        $startHour = 7; 
        $endHour = 23;  
        
        $occupied = Reserva::where('cancha_id', $this->cancha->id)
            ->whereDate('start_time', $this->date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        $firstAvailableFound = false;
        $now = Carbon::now(); // Hora actual del servidor

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            // Creamos la fecha/hora del slot
            $slotTime = Carbon::parse($this->date)->setHour($hour)->setMinute(0)->setSecond(0);
            
            // 1. Verificamos si choca con reservas
            $isOccupied = $occupied->filter(function ($reserva) use ($slotTime) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                return $slotTime->greaterThanOrEqualTo($resStart) && $slotTime->lessThan($resEnd);
            })->isNotEmpty();

            // 2. 🟢 CORRECCIÓN: Verificamos si la hora YA PASÓ (solo si es hoy)
            if (!$isOccupied && $slotTime->isPast()) {
                $isOccupied = true; // Lo marcamos como ocupado/no disponible
            }

            $value = $slotTime->format('H:i');
            $label = $slotTime->format('h:i A');

            $this->timeSlots[] = [
                'value' => $value,
                'label' => $label,
                'occupied' => $isOccupied
            ];

            // Auto-seleccionar el primero libre
            if (!$isOccupied && !$firstAvailableFound) {
                $this->time = $value;
                $firstAvailableFound = true;
            }
        }

        if (!$firstAvailableFound) {
            $this->time = '';
        }
    }

    public function calculatePrice()
    {
        $this->total_price = $this->cancha->price_per_hour * (int) $this->duration;
    }

    public function submitReserva()
    {
        $this->validate();

        if (!$this->time) {
            $this->addError('time', 'Por favor selecciona un horario disponible.');
            return;
        }

        $start_time = Carbon::createFromFormat('Y-m-d H:i', $this->date . ' ' . $this->time);
        $end_time = $start_time->copy()->addHours((int) $this->duration);

        if (!$this->isRangeAvailable($start_time, $end_time)) {
             $this->addError('availability', 'El bloque de tiempo seleccionado choca con otra reserva.');
             return;
        }

        // Crear Request simulado
        $fakeRequest = new Request([
            'cancha_id' => $this->cancha->id,
            'start_time' => $start_time->format('Y-m-d H:i:s'),
            'end_time' => $end_time->format('Y-m-d H:i:s'),
            'total_price' => $this->total_price,
        ]);
        
        try {
            // Llamamos al controlador
            $controller = new \App\Http\Controllers\ReservaController();
            $controller->store($fakeRequest);

            session()->flash('success', '¡Reserva realizada con éxito!');
            
            // 🟢 CORRECCIÓN REDIRECCIÓN: Quitamos 'navigate: true' para asegurar compatibilidad
            return redirect()->route('reservas.user.index');

        } catch (ValidationException $e) {
            // Si el controlador rechaza (ej: 'after:now'), capturamos el error
            // y lo mostramos en el campo 'time' o 'availability'
            $this->addError('availability', 'Error de validación: La hora seleccionada no es válida o ya pasó.');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error: ' . $e->getMessage());
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