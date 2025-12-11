<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Carbon\Carbon;

class ReservaController extends Controller
{
    use AuthorizesRequests; 

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
            'status'      => 'pending', // Nace como pendiente
        ]);

        return redirect()->route('reservas.user.index')
            ->with('success', '¡Solicitud de reserva enviada! Espera la confirmación del dueño.');
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
        // 1. Obtener ID del usuario autenticado (dueño)
        $userId = Auth::id();

        // 2. Obtener IDs de sus canchas
        $canchaIds = Cancha::where('user_id', $userId)->pluck('id');

        // 3. Obtener reservas de ESAS canchas
        $reservas = Reserva::whereIn('cancha_id', $canchaIds)
            ->with('user', 'cancha') // Cargar relaciones
            ->latest()
            ->paginate(10); 

        // Retornar vista correcta en la carpeta owner
        return view('owner.reservas.index', compact('reservas'));
    }

    // -------------------------------------------------------------------------
    // ACCIONES DUEÑO (ACTUALIZADO)
    // -------------------------------------------------------------------------

    /**
     * Permite al dueño actualizar el estado (Adelantos, Pagos, Cancelaciones).
     */
    public function updateStatus(Reserva $reserva, Request $request)
    {
        // 🔒 SEGURIDAD: Policy 'updateStatus' debe permitir esto
        $this->authorize('updateStatus', $reserva);

        // 🟢 VALIDACIÓN ACTUALIZADA: Acepta los nuevos estados de pago
        $request->validate([
            'status' => 'required|in:pending,advance_paid,fully_paid,cancelled',
        ]);

        $reserva->update([
            'status' => $request->status,
        ]);
        
        // Mensaje personalizado según el estado para mejor feedback
        $msg = match($request->status) {
            'advance_paid' => 'Adelanto registrado correctamente. 🟡',
            'fully_paid'   => 'Pago completo registrado. ¡Cancha pagada! 🟢',
            'cancelled'    => 'La reserva ha sido cancelada. 🔴',
            'pending'      => 'Estado cambiado a pendiente.',
            default        => 'Estado de la reserva actualizado.',
        };

        return back()->with('success', $msg);
    }

    // -------------------------------------------------------------------------
    // ACCIONES USUARIO (Cancelar / Editar)
    // -------------------------------------------------------------------------

    /**
     * Permite al USUARIO cancelar su propia reserva.
     */
    public function cancelUser(Reserva $reserva)
    {
        // 🔒 SEGURIDAD: Policy
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
        // 🔒 SEGURIDAD: Policy 'view' verifica propiedad
        $this->authorize('view', $reserva); 

        // Validaciones extra de negocio
        if ($reserva->status === 'cancelled') {
            return back()->with('error', 'No puedes editar una reserva cancelada.');
        }

        if ($reserva->start_time < now()) {
            return back()->with('error', 'No puedes editar una reserva pasada.');
        }

        return view('reservas.edit', compact('reserva'));
    }
}