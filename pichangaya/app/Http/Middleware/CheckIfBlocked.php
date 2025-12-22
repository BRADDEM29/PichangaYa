<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBlocked
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si hay alguien conectado y si su campo 'is_blocked' es true (1)
        if (Auth::check() && Auth::user()->is_blocked) {
            
            // 1. Cerrar la sesión inmediatamente
            Auth::guard('web')->logout();

            // 2. Invalidar la sesión actual (seguridad)
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 3. Redirigir al Login con mensaje de error
            // Usamos 'withErrors' para que aparezca rojo en el formulario de login
            return redirect()->route('login')->withErrors([
                'email' => '⛔ TU CUENTA ESTÁ BLOQUEADA. Contacta con la administración para más detalles.',
            ]);
        }

        return $next($request);
    }
}