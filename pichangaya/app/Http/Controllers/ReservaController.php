<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importante
use Carbon\Carbon;

class ReservaController extends Controller
{
    use AuthorizesRequests; // Habilita $this->authorize

    /**
     * Valida si un horario solicitado está disponible.
     */
    protected function isAvailable(int $canchaId, string $startTime, string $endTime): bool
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $existingReserva = Reserva::where('cancha_id', $canchaId)
            ->where('end_time', '>', $start)
            ->where('start_time', '<', $end)
            ->where('status', '!=', 'cancelled')
            ->exists();
            
        return !$existingReserva;
    }

    // -------------------------------------------------------------------------
    // CLIENTE: CREAR RESERVA
    // -------------------------------------------------------------------------
    public function create(Cancha $cancha)
    {
        return view('reservas.create', compact('cancha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'start_time' => 'required|date|after:now',
            'end_time'   => 'required|date|after:start_time',
        ]);

        $cancha = Cancha::findOrFail($request->cancha_id);
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        if (!$this->isAvailable($cancha->id, $start, $end)) {
            return back()
                ->withInput()
                ->withErrors(['availability' => 'El horario solicitado ya está ocupado. Por favor elige otro.']);
        }

        $hours = $start->diffInMinutes($end) / 60;
        $totalPrice = round($hours * $cancha->price_per_hour, 2);

        Reserva::create([
            'cancha_id'   => $cancha->id,
            'user_id'     => Auth::id(),
            'start_time'  => $start,
            'end_time'    => $end,
            'total_price' => $totalPrice,
            'status'      => 'confirmed', 
        ]);

        return redirect()->route('reservas.user.index')
            ->with('success', '¡Reserva creada exitosamente!');
    }

    // -------------------------------------------------------------------------
    // LISTADOS (Index)
    // -------------------------------------------------------------------------
    
    /**
     * Mis Reservas (Cliente)
     */
    public function userReservasIndex()
    {
        $reservas = Auth::user()->reservas()
                        ->with('cancha')
                        ->latest()
                        ->paginate(10); 
        
        return view('reservas.user-reservas-index', compact('reservas'));
    }

    /**
     * Reservas en mis Canchas (Dueño)
     */
    public function ownerReservasIndex()
    {
        // 🟢 CORRECCIÓN: Usamos 'user_id' en lugar de 'owner_id' para coincidir con el modelo Cancha
        $canchaIds = Cancha::where('user_id', Auth::id())->pluck('id');

        $reservas = Reserva::whereIn('cancha_id', $canchaIds)
            ->with('user', 'cancha') 
            ->latest()
            ->paginate(10); 

        return view('owner-reservas-index', compact('reservas'));
    }

    // -------------------------------------------------------------------------
    // ACCIONES DUEÑO
    // -------------------------------------------------------------------------

    /**
     * Permite al dueño actualizar el estado (confirmar/cancelar).
     */
    public function updateStatus(Reserva $reserva, Request $request)
    {
        // 🔒 SEGURIDAD: Usamos la Policy 'updateStatus'
        $this->authorize('updateStatus', $reserva);

        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $reserva->update([
            'status' => $request->status,
        ]);
        
        return back()->with('success', 'Estado actualizado correctamente.');
    }

    // -------------------------------------------------------------------------
    // ACCIONES USUARIO (Cancelar / Editar)
    // -------------------------------------------------------------------------

    /**
     * Permite al USUARIO cancelar su propia reserva.
     */
    public function cancelUser(Reserva $reserva)
    {
        // 🔒 SEGURIDAD: Usamos la Policy 'cancel'
        $this->authorize('cancel', $reserva);

        // Validación de Negocio: No cancelar fechas pasadas
        if ($reserva->start_time < now()) {
             return back()->with('error', 'No puedes cancelar una reserva que ya pasó.');
        }

        $reserva->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }

    /**
     * Muestra el formulario de edición para el USUARIO.
     */
    public function editUser(Reserva $reserva)
    {
        // 🔒 SEGURIDAD: Verificamos que sea el dueño de la reserva
        // Usamos 'view' o 'cancel' como proxy de propiedad, o idealmente 'update'
        $this->authorize('view', $reserva); 

        if ($reserva->user_id !== Auth::id()) {
             abort(403); // Doble chequeo por si la policy view es permisiva
        }

        if ($reserva->status === 'cancelled') {
            return back()->with('error', 'No puedes editar una reserva cancelada.');
        }

        if ($reserva->start_time < now()) {
            return back()->with('error', 'No puedes editar una reserva pasada.');
        }

        return view('reservas.edit', compact('reserva'));
    }
}