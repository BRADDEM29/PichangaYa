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
    
    // Lista de horarios inteligentes
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
        
        // Generar horarios y calcular precio inicial
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
        $startHour = 7; // 7:00 AM
        $endHour = 23;  // 11:00 PM
        
        // Buscar reservas ocupadas
        $occupied = Reserva::where('cancha_id', $this->cancha->id)
            ->whereDate('start_time', $this->date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        $firstAvailableFound = false;

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slotTime = Carbon::parse($this->date)->setHour($hour)->setMinute(0)->setSecond(0);
            
            // Verificar si choca con alguna reserva
            $isOccupied = $occupied->filter(function ($reserva) use ($slotTime) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                return $slotTime->greaterThanOrEqualTo($resStart) && $slotTime->lessThan($resEnd);
            })->isNotEmpty();

            $value = $slotTime->format('H:i'); // Valor interno (24h)
            $label = $slotTime->format('h:i A'); // Valor visible (12h)

            $this->timeSlots[] = [
                'value' => $value,
                'label' => $label,
                'occupied' => $isOccupied
            ];

            // Auto-seleccionar el primero libre si no está ocupado
            if (!$isOccupied && !$firstAvailableFound) {
                $this->time = $value;
                $firstAvailableFound = true;
            }
        }

        // Si no se encontró ninguno libre o todo está ocupado
        if (!$firstAvailableFound) {
            $this->time = '';
        }
    }

    public function calculatePrice()
    {
        // 🟢 CORRECCIÓN: Convertir a (int) para evitar errores matemáticos
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
        
        // 🟢 CORRECCIÓN CRÍTICA: Convertir duración a (int) para evitar el TypeError de Carbon
        $end_time = $start_time->copy()->addHours((int) $this->duration);

        // Validación extra: verificar que el bloque completo (ej. 2 horas) esté libre
        if (!$this->isRangeAvailable($start_time, $end_time)) {
             $this->addError('availability', 'El bloque de tiempo seleccionado choca con otra reserva.');
             return;
        }

        $fakeRequest = new Request([
            'cancha_id' => $this->cancha->id,
            'start_time' => $start_time->format('Y-m-d H:i:s'),
            'end_time' => $end_time->format('Y-m-d H:i:s'),
            'total_price' => $this->total_price,
        ]);
        
        try {
            $controller = new \App\Http\Controllers\ReservaController();
            $controller->store($fakeRequest);

            session()->flash('success', '¡Reserva realizada con éxito!');
            
            // Recargar slots para reflejar la nueva ocupación
            $this->generateTimeSlots();
            
            // Redirigir al usuario a su lista de reservas
            return $this->redirect(route('reservas.user.index'), navigate: true);

        } catch (ValidationException $e) {
            throw $e; 
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