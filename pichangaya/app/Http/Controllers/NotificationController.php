<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\NotificationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva; // 🟢 IMPORTANTE: Necesitamos el modelo para verificar el estado real

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marcar una notificación individual como leída.
     * 🟢 MEJORA: Si la reserva sigue en PENDING, redirige pero NO quita la notificación.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // ---------------------------------------------------------------------
        // 🛡️ LÓGICA DE PROTECCIÓN (Por Estado de Reserva)
        // ---------------------------------------------------------------------
        // Verificamos si esta notificación está vinculada a una reserva
        if (isset($notification->data['reserva_id'])) {
            
            $reserva = Reserva::find($notification->data['reserva_id']);

            // Si la reserva existe Y su estado es 'pending' (Pendiente)
            // ENTONCES: Redirigimos al admin/owner para que trabaje, 
            // PERO NO marcamos la notificación como leída. Se queda ahí.
            if ($reserva && $reserva->status === 'pending') {
                
                // Si tiene URL, lo mandamos a trabajar
                if (isset($notification->data['url'])) {
                    return redirect($notification->data['url']);
                }
                return back();
            }
        }
        // ---------------------------------------------------------------------

        // Si la reserva YA FUE ATENDIDA (aceptada, cancelada, etc),
        // entonces sí permitimos borrar la notificación.
        $notification->markAsRead();

        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return back();
    }

    /**
     * 🟢 MARCAR TODO LEÍDO (CON PROTECCIÓN DE ESTADO PENDIENTE)
     */
    public function markAllRead()
    {
        $user = Auth::user();
        
        // Obtenemos solo las NO leídas
        $unreadNotifications = $user->unreadNotifications;
        $markedCount = 0;

        foreach ($unreadNotifications as $notification) {
            
            // -----------------------------------------------------------------
            // 🛡️ LÓGICA DE PROTECCIÓN MASIVA
            // -----------------------------------------------------------------
            if (isset($notification->data['reserva_id'])) {
                
                $reserva = Reserva::find($notification->data['reserva_id']);

                // Si la reserva sigue en 'pending', LA SALTAMOS.
                // No dejamos que el botón "Marcar todo" la borre.
                if ($reserva && $reserva->status === 'pending') {
                    continue; 
                }
            }
            // -----------------------------------------------------------------
            
            // Si no es de reserva, o ya no está pendiente, la marcamos.
            $notification->markAsRead();
            $markedCount++;
        }

        if ($markedCount > 0) {
            return back()->with('success', "Se marcaron $markedCount notificaciones como leídas.");
        } else {
            // Mensaje explicativo
            return back()->with('info', 'No se borraron las alertas de reservas que aún están PENDIENTES de atención.');
        }
    }
}