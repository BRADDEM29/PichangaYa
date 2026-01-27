<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Livewire\CanchaReservaForm.php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; // 🟢 Necesario para el atributo #[On]
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

    /**
     * 🟢 ESCUCHADOR PARA ACTUALIZAR LA CANCHA DESDE EL FORMULARIO DE TORNEO
     */
    #[On('updateCancha')]
    public function updateCancha($id)
    {
        $nuevaCancha = Cancha::find($id);
        if ($nuevaCancha) {
            $this->cancha = $nuevaCancha;
            $this->resetSelection(); // Limpiamos selección previa al cambiar de cancha
            $this->generateTimeSlots();
            $this->calculatePrice();
        }
    }

    public function mount(Cancha $cancha, Reserva $reserva = null)
    {
        $this->cancha = $cancha;
        
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
        foreach ($this->timeSlots as $slot) {
            if ($slot['value'] === $clickedTime && ($slot['disabled'] ?? false)) {
                return;
            }
        }

        if (!$this->time) {
            $this->time = $clickedTime;
            $this->duration = 1;
            $this->calculatePrice();
            
            // 🟢 DESPACHAR EVENTO PARA EL FORMULARIO DE TORNEOS
            $this->dispatch('time-selected', [
                'date' => $this->date,
                'time' => $this->time
            ]);
            return;
        }

        $start = Carbon::parse($this->date . ' ' . $this->time);
        $clicked = Carbon::parse($this->date . ' ' . $clickedTime);
        $endOfSelection = $start->copy()->addHours($this->duration);

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

            $this->dispatch('time-selected', [
                'date' => $this->date,
                'time' => $this->time
            ]);
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

        $this->dispatch('time-selected', [
            'date' => $this->date,
            'time' => $this->time
        ]);
    }

    public function resetSelection()
    {
        $this->time = '';
        $this->duration = 1;
        $this->total_price = 0;

        $this->dispatch('time-selected', [
            'date' => $this->date,
            'time' => ''
        ]);
    }

    public function calculatePrice()
    {
        if ($this->duration > 0 && isset($this->cancha)) {
            $this->total_price = $this->cancha->price_per_hour * $this->duration;
        } else {
            $this->total_price = 0;
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

            foreach ($occupied as $reserva) {
                $resStart = Carbon::parse($reserva->start_time);
                $resEnd = Carbon::parse($reserva->end_time);
                
                if ($currentSlot->lt($resEnd) && $slotEnd->gt($resStart)) {
                    
                    if ($this->reservaEdicion && $this->reservaEdicion->id === $reserva->id) {
                        continue; 
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
                            } else {
                                $isOccupied = true; 
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