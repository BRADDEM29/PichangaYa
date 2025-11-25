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
        // 1. Verificar si el usuario está logueado y si su rol coincide con el requerido
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // 2. Si no coincide, lo sacamos (Error 403: Prohibido)
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}