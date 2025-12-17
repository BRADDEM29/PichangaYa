<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; // Para el correo (Opcional si usas Notificaciones)

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
        $user = Auth::user();

        // ---------------------------------------------------------
        // 1. REGLA: SOLO UNA RESERVA PENDIENTE A LA VEZ
        // ---------------------------------------------------------
        $pendingReservation = Reserva::where('user_id', $user->id)
                                     ->where('status', 'pending')
                                     ->exists();

        if ($pendingReservation) {
            return back()->with('error', '⚠️ Tienes una reserva pendiente de pago. Debes completarla o cancelarla antes de hacer otra.');
        }

        // ---------------------------------------------------------
        // 2. REGLA: 3 STRIKES (ADVERTENCIA)
        // ---------------------------------------------------------
        // Contamos cuántas reservas se le han cancelado automáticamente (o manualmente por falta de pago)
        $cancelledCount = Reserva::where('user_id', $user->id)
                                 ->where('status', 'cancelled')
                                 ->count();

        $warningMessage = null;
        if ($cancelledCount >= 3) {
            $warningMessage = "⚠️ Advertencia: Tienes $cancelledCount reservas canceladas anteriormente. Si continúas reservando sin pagar, tu cuenta podría ser suspendida.";
        }

        // ---------------------------------------------------------
        // 3. VALIDACIÓN DE HORA FLEXIBLE (Hasta 30 min iniciada la hora)
        // ---------------------------------------------------------
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'start_time' => 'required|date', 
            'end_time'   => 'required|date|after:start_time',
        ]);

        $start = Carbon::parse($request->start_time);
        $now = Carbon::now();

        // Si la hora de inicio + 30 minutos ya pasó, entonces ya no se puede reservar.
        // Ejemplo: Son las 9:40. Intento reservar a las 9:00. 9:00 + 30min = 9:30. 9:30 es pasado -> Error.
        if ($start->copy()->addMinutes(30)->isPast()) {
            return back()->withErrors(['start_time' => 'El horario de inicio debe ser una fecha posterior a ahora (o máximo 30 min de tolerancia).']);
        }

        // Verificar disponibilidad
        if (!$this->isAvailable($request->cancha_id, $request->start_time, $request->end_time)) {
            return back()->with('error', 'Lo sentimos, este horario ya ha sido reservado por otra persona.');
        }

        // ---------------------------------------------------------
        // 4. CREAR LA RESERVA
        // ---------------------------------------------------------
        $cancha = Cancha::findOrFail($request->cancha_id);
        
        // Calcular precio (Ejemplo simple, ajusta según tu lógica de precio)
        $hours = $start->diffInHours(Carbon::parse($request->end_time));
        // Si es media hora o fracción, ajustamos (Carbon float diff)
        $hoursFloat = $start->diffInMinutes(Carbon::parse($request->end_time)) / 60;
        $totalPrice = $cancha->price_per_hour * $hoursFloat;

        $reserva = Reserva::create([
            'cancha_id'   => $cancha->id,
            'user_id'     => $user->id,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'total_price' => $totalPrice,
            'status'      => 'pending', // Nace pendiente
        ]);

        // ---------------------------------------------------------
        // 5. ENVÍO DE CORREO (Notificación)
        // ---------------------------------------------------------
        // Aquí podrías disparar un Mailable de Laravel. 
        // Por simplicidad en este paso, asumiremos que la configuración .env está lista.
        // Mail::to($user->email)->send(new \App\Mail\ReservaPendiente($reserva));

        // Mensaje de éxito + Advertencia de strikes si aplica
        $successMsg = '¡Reserva registrada! Tienes 10 MINUTOS para realizar el pago o confirmar, de lo contrario se cancelará automáticamente.';
        if ($warningMessage) {
            $successMsg .= " " . $warningMessage;
        }

        return redirect()->route('reservas.user.index')->with('success', $successMsg);
    }

    // -------------------------------------------------------------------------
    // LISTADOS (Index)
    // -------------------------------------------------------------------------
    
    /**
     * Mis Reservas (Cliente)
     */
    public function userReservasIndex()
    {
        $reservas = Reserva::where('user_id', Auth::id())
                    ->with('cancha')
                    ->orderBy('created_at', 'desc')
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