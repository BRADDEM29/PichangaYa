<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Muestra la vista con todas las notificaciones (historial).
     */
    public function index()
    {
        // Obtiene todas, leídas y no leídas, paginadas
        $notifications = auth()->user()->notifications()->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marca una notificación como leída y redirige a su destino.
     * ⚠️ CAMBIO IMPORTANTE: El nombre ahora es markAsRead para coincidir con tu ruta.
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        // 1. Marcar como leída
        $notification->markAsRead();

        // 2. Extraer la URL de destino de la data (si existe)
        $destinationUrl = $notification->data['url'] ?? route('home');

        // 3. Redirigir
        return redirect($destinationUrl);
    }
}