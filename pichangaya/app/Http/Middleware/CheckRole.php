<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
{
    // Si no está logueado, fuera.
    if (!Auth::check()) {
        abort(403);
    }

    $userRole = Auth::user()->role;

    // AQUI ESTÁ EL TRUCO:
    // Pasa si: El rol coincide O SI EL USUARIO ES ADMIN (Llave Maestra)
    if ($userRole === $role || $userRole === 'admin') {
        return $next($request);
    }

    abort(403, 'No tienes permiso para acceder a esta sección.');
}
}