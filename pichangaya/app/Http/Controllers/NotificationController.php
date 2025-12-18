<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    // ⚠️ EL NOMBRE DEBE SER markAsRead para coincidir con la ruta
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();

        // Redirigir a la URL que viene en la notificación o al inicio
        $destinationUrl = $notification->data['url'] ?? route('home');
        return redirect($destinationUrl);
    }
}