<?php
// C:\laragon\www\PichangaYa\pichangaya\bootstrap\app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Tus Alias existentes (para usar 'role:admin', etc.)
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 2. 👇 NUEVO: BLOQUEO GLOBAL DE USUARIOS
        // Esto asegura que se verifique si está bloqueado en CADA petición web.
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckIfBlocked::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();