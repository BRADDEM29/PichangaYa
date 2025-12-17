<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminLoginAlert; // Asegúrate de crear este Mail (es igual al de status)

class SendAdminLoginAlert
{
    public function handle(Login $event): void
    {
        // Solo si es ADMIN
        if ($event->user->role === 'admin') {
            // Enviar alerta al correo del admin
             // Mail::to($event->user->email)->send(new AdminLoginAlert($event->user));
             
             // O simplemente un log por ahora para probar
             \Log::info("ALERTA DE SEGURIDAD: Admin {$event->user->email} inició sesión a las " . now());
        }
    }
}