<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

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
        'duration' => 'required|integer|min:1|max:4',
    ];

    public function mount(Reserva $reserva)
    {
        $this->reserva = $reserva;
        
        // Precargar los datos de la reserva existente
        $this->date = $reserva->start_time->format('Y-m-d');
        $this->time = $reserva->start_time->format('H:i');
        $this->duration = $reserva->start_time->diffInHours($reserva->end_time);
        
        $this->generateTimeSlots();
        $this->calculatePrice();
    }

    public function updatedDate() { $this->generateTimeSlots(); }
    
    public function updated($propertyName) { 
        if(in_array($propertyName, ['date','time','duration'])) $this->calculatePrice(); 
    }

    public function calculatePrice() { 
        $this->total_price = $this->reserva->cancha->price_per_hour * (int)$this->duration; 
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];
        $startHour = 7; 
        $endHour = 23;
        
        // Buscar reservas ocupadas EXCLUYENDO la reserva actual
        $occupied = Reserva::where('cancha_id', $this->reserva->cancha_id)
            ->where('id', '!=', $this->reserva->id) // 🟢 Clave: Excluirse a sí misma
            ->whereDate('start_time', $this->date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slotTime = Carbon::parse($this->date)->setHour($hour)->setMinute(0)->setSecond(0);
            
            $isOccupied = $occupied->filter(function ($r) use ($slotTime) {
                $resStart = Carbon::parse($r->start_time);
                $resEnd = Carbon::parse($r->end_time);
                return $slotTime->greaterThanOrEqualTo($resStart) && $slotTime->lessThan($resEnd);
            })->isNotEmpty();

            $this->timeSlots[] = [
                'value' => $slotTime->format('H:i'),
                'label' => $slotTime->format('h:i A'),
                'occupied' => $isOccupied
            ];
        }
    }

    public function updateReserva()
    {
        $this->validate();

        $start_time = Carbon::createFromFormat('Y-m-d H:i', $this->date . ' ' . $this->time);
        $end_time = $start_time->copy()->addHours((int)$this->duration);

        // Validar que el rango completo esté libre (excluyendo la reserva actual)
        $exists = Reserva::where('cancha_id', $this->reserva->cancha_id)
            ->where('id', '!=', $this->reserva->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function ($query) use ($start_time, $end_time) {
                $query->where(function ($q) use ($start_time, $end_time) {
                    $q->where('start_time', '>=', $start_time)
                      ->where('start_time', '<', $end_time);
                })->orWhere(function ($q) use ($start_time, $end_time) {
                    $q->where('end_time', '>', $start_time)
                      ->where('end_time', '<=', $end_time);
                });
            })->exists();

        if ($exists) {
            $this->addError('availability', 'El nuevo horario choca con otra reserva existente.');
            return;
        }

        // Actualizar
        $this->reserva->update([
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total_price' => $this->total_price,
        ]);

        session()->flash('success', 'Reserva actualizada correctamente.');
        return redirect()->route('reservas.user.index');
    }

    public function render()
    {
        return view('livewire.edit-reserva-form');
    }
}