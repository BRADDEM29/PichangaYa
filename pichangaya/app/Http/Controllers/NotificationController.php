<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\NotificationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;

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
     * 🟢 NUEVO MÉTODO AJAX PARA LA CAMPANITA (TIEMPO REAL)
     */
    public function markAsReadAjax($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            
            // 🛡️ LÓGICA DE PROTECCIÓN (AJAX): 
            // Si es una reserva pendiente, NO la marcamos como leída en la BD,
            // aunque el usuario haya hecho clic. Así persiste hasta que se atienda.
            if (isset($notification->data['reserva_id'])) {
                $reserva = Reserva::find($notification->data['reserva_id']);
                if ($reserva && $reserva->status === 'pending') {
                    // Retornamos success para que JS redirija, pero NO ejecutamos markAsRead()
                    return response()->json(['success' => true, 'status' => 'pending']);
                }
            }

            // Si es normal o ya atendida, la marcamos como leída.
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Notificación no encontrada'], 404);
    }

    /**
     * Marcar una notificación individual como leída (Método tradicional).
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // 🛡️ PROTECCIÓN PENDING
        if (isset($notification->data['reserva_id'])) {
            $reserva = Reserva::find($notification->data['reserva_id']);
            if ($reserva && $reserva->status === 'pending') {
                if (isset($notification->data['url'])) {
                    return redirect($notification->data['url']);
                }
                return back();
            }
        }

        $notification->markAsRead();

        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return back();
    }

    /**
     * Marcar todo leído (Con protección).
     */
    public function markAllRead()
    {
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications;
        $markedCount = 0;

        foreach ($unreadNotifications as $notification) {
            // 🛡️ PROTECCIÓN MASIVA
            if (isset($notification->data['reserva_id'])) {
                $reserva = Reserva::find($notification->data['reserva_id']);
                if ($reserva && $reserva->status === 'pending') {
                    continue; // No borrar si está pendiente
                }
            }
            
            $notification->markAsRead();
            $markedCount++;
        }

        if ($markedCount > 0) {
            return back()->with('success', "Se marcaron $markedCount notificaciones como leídas.");
        } else {
            return back()->with('info', 'No se borraron las alertas de reservas que aún están PENDIENTES de atención.');
        }
    }

    /**
     * 🟢 POLLING: Revisar nuevas notificaciones y devolver HTML.
     */
    public function checkNew()
    {
        $user = Auth::user();
        $isStaff = in_array($user->role, ['admin', 'owner']);

        // 1. Calcular variables de alerta (igual que en navigation-menu)
        $alertEmail = !$isStaff && !$user->hasVerifiedEmail();
        $alertPhone = !$isStaff && is_null($user->phone_verified_at);

        // 2. Obtener y FILTRAR notificaciones (Igual que en la vista)
        $filteredNotifications = $user->unreadNotifications->filter(function ($notification) {
            if (isset($notification->data['reserva_id'])) {
                $reserva = \App\Models\Reserva::find($notification->data['reserva_id']);
                if (!$reserva || $reserva->status !== 'pending') {
                    return false;
                }
            }
            return true;
        });

        // 3. Renderizar el HTML del partial que creamos en el Paso 1
        $html = view('navigation.partials.notifications-list', compact('filteredNotifications', 'alertEmail', 'alertPhone'))->render();

        return response()->json([
            'count' => $filteredNotifications->count(),
            'html' => $html
        ]);
    }
}