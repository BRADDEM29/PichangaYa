<?php

namespace App\Http\Controllers;
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\ReservaController.php

use App\Models\Reserva;
use App\Models\Cancha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

// 👇 IMPORTANTE: Las clases de Notificación
use App\Notifications\ReservaEstadoActualizado; // Para cambios de estado
use App\Notifications\NuevaReservaNotification; // ✅ ÚNICA CLASE para Cliente, Admin y Dueño
use App\Notifications\ReservaCancelada;         // 🔴 NUEVA: Para cancelaciones (Manuales o Automáticas)

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
        // 🔒 0. SEGURIDAD: VERIFICAR BLOQUEO DE CUENTA
        // ---------------------------------------------------------
        if ($user->is_blocked) {
            return redirect()->back()->with('error', '⛔ Tu cuenta ha sido bloqueada indefinidamente por incumplimiento reiterado de reservas. Contacta a soporte.');
        }

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
        // 2. REGLA: 3 STRIKES (ADVERTENCIA VISUAL)
        // ---------------------------------------------------------
        // Nota: La lógica fuerte de bloqueo está en el Observer, aquí solo avisamos
        $warningMessage = null;
        if ($user->consecutive_cancellations >= 3) {
            $warningMessage = "⚠️ ADVERTENCIA CRÍTICA: Tienes " . $user->consecutive_cancellations . " cancelaciones consecutivas. Una más y tu cuenta será bloqueada.";
        }

        // ---------------------------------------------------------
        // 3. VALIDACIÓN DE HORA Y DISPONIBILIDAD
        // ---------------------------------------------------------
        $request->validate([
            'cancha_id'  => 'required|exists:canchas,id',
            'start_time' => 'required|date', 
            'end_time'   => 'required|date|after:start_time',
        ]);

        $start = Carbon::parse($request->start_time);

        // Validación de tolerancia (30 min)
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
        
        // Calcular precio
        $hoursFloat = $start->diffInMinutes(Carbon::parse($request->end_time)) / 60;
        $totalPrice = $cancha->price_per_hour * $hoursFloat;

        $reserva = Reserva::create([
            'cancha_id'   => $cancha->id,
            'user_id'     => $user->id,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'total_price' => $totalPrice,
            'status'      => 'pending', 
        ]);

        // =========================================================================
        // 🟢 SISTEMA DE NOTIFICACIONES UNIFICADO (Sin Observer 'created')
        // =========================================================================
        
        // 1. Notificar al CLIENTE
        $user->notify(new NuevaReservaNotification($reserva));

        // 2. Notificar al DUEÑO de la cancha
        if ($cancha->user && $cancha->user->id !== $user->id) {
            $cancha->user->notify(new NuevaReservaNotification($reserva));
        }

        // 3. Notificar a todos los ADMINS (Excluyendo al usuario actual si es admin)
        $admins = User::where('role', 'admin')
                      ->where('id', '!=', $user->id)
                      ->get();

        if ($admins->count() > 0) {
            Notification::send($admins, new NuevaReservaNotification($reserva));
        }
        
        // =========================================================================

        // ---------------------------------------------------------
        // 5. RESPUESTA CON DETALLES PARA LA NOTIFICACIÓN FLOTANTE
        // ---------------------------------------------------------
        
        // Construimos el mensaje de éxito
        $successMsg = '¡Reserva registrada! Tienes 10 MINUTOS para realizar el pago.';
        if ($warningMessage) {
            $successMsg .= " " . $warningMessage;
        }

        // Array con datos detallados para la alerta
        $datosNotificacion = [
            'expiry' => $reserva->created_at->addMinutes(10)->timestamp,
            'cancha' => $cancha->name,
            'id'     => $reserva->id
        ];

        return redirect()->route('reservas.user.index')
            ->with('reservation_pending_details', $datosNotificacion)
            ->with('success', $successMsg);
    }

    // -------------------------------------------------------------------------
    // LISTADOS (Index)
    // -------------------------------------------------------------------------
    
    public function userReservasIndex()
    {
        $reservas = Reserva::where('user_id', Auth::id())
                    ->with('cancha')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('reservas.user-reservas-index', compact('reservas'));
    }

    public function ownerReservasIndex()
    {
        $userId = Auth::id();
        $canchaIds = Cancha::where('user_id', $userId)->pluck('id');

        $reservas = Reserva::whereIn('cancha_id', $canchaIds)
            ->with('user', 'cancha') 
            ->latest()
            ->paginate(10); 

        return view('owner.reservas.index', compact('reservas'));
    }

    // -------------------------------------------------------------------------
    // ACCIONES DUEÑO
    // -------------------------------------------------------------------------

    public function updateStatus(Reserva $reserva, Request $request)
    {
        $this->authorize('updateStatus', $reserva);

        $request->validate([
            'status' => 'required|in:pending,advance_paid,fully_paid,cancelled',
        ]);

        // 1. Actualizar el estado en la BD
        // 🟢 IMPORTANTE: Al hacer esto, se disparará el OBSERVER 'updated'
        // que contiene la lógica de STRIKES y BLOQUEO.
        $reserva->update([
            'status' => $request->status,
        ]);

        // =========================================================
        // Notificar al Cliente sobre el cambio de estado
        // =========================================================
        try {
            // Si es cancelado por el dueño, usamos la nueva notificacion
            if ($request->status === 'cancelled') {
                 $reserva->user->notify(new ReservaCancelada($reserva));
            } else {
                 $reserva->user->notify(new ReservaEstadoActualizado($reserva));
            }
        } catch (\Exception $e) {
            // Log::error('Error enviando notificacion: ' . $e->getMessage());
        }

        // 3. Mensaje de Feedback para el Dueño
        $msg = match($request->status) {
            'advance_paid' => 'Adelanto registrado y cliente notificado. 🟡',
            'fully_paid'   => 'Pago completo registrado y cliente notificado. 🟢',
            'cancelled'    => 'Reserva cancelada y cliente notificado. 🔴',
            'pending'      => 'Estado cambiado a pendiente.',
            default        => 'Estado actualizado correctamente.',
        };

        return back()->with('success', $msg);
    }

    // -------------------------------------------------------------------------
    // ACCIONES USUARIO (Cancelar / Editar)
    // -------------------------------------------------------------------------

    public function cancelUser(Reserva $reserva)
    {
        $this->authorize('cancel', $reserva);

        if ($reserva->start_time < now()) {
             return back()->with('error', 'No puedes cancelar una reserva que ya pasó.');
        }

        // 🟢 Al cancelar, el OBSERVER 'updated' aumentará el contador de strikes
        // Usamos save() explícito para asegurar eventos y claridad
        $reserva->status = 'cancelled';
        $reserva->save();

        // 🔔 Enviar alerta al usuario (email de confirmación de cancelación)
        try {
            $reserva->user->notify(new ReservaCancelada($reserva));
        } catch (\Exception $e) {
            // Manejo silencioso de error de email
        }

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }

    public function editUser(Reserva $reserva)
    {
        $this->authorize('view', $reserva); 

        if ($reserva->status === 'cancelled') {
            return back()->with('error', 'No puedes editar una reserva cancelada.');
        }

        if ($reserva->start_time < now()) {
            return back()->with('error', 'No puedes editar una reserva pasada.');
        }

        return view('reservas.edit', compact('reserva'));
    }
}