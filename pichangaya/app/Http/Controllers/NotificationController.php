<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Obtenemos todas las notificaciones (leídas y no leídas) paginadas
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Marcar como leída
        $notification->markAsRead();

        // Redirigir a la URL que guardamos en la notificación (la reserva)
        return redirect($notification->data['url'] ?? route('reservas.user.index'));
    }
}