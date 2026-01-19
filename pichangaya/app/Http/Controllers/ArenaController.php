<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\ArenaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament; // 🟢 Usamos el nuevo modelo de Torneos
use App\Models\Lobby;      // Mantenemos los Lobbies para el matchmaking

class ArenaController extends Controller
{
    public function index()
    {
        // 1. Cargar Torneos (Copa Vector Pro y otros)
        // Usamos la lógica mejorada: Traer torneos abiertos, activos o finalizados, los más recientes primero.
        $championships = Tournament::whereIn('status', ['open', 'active', 'finished'])
            ->latest()
            ->get();

        // 2. Cargar Lobbys activos (Mantenido del código original para no romper la vista)
        // Solo mostramos lobbys que NO estén llenos y que NO hayan expirado
        $activeLobbies = Lobby::where('status', 'searching')
            ->where('expires_at', '>', now())
            ->with(['sport', 'district']) // Asumiendo que estas relaciones existen en el modelo Lobby
            ->take(5)
            ->get();

        // Retornamos ambas variables a la vista
        return view('arena.index', compact('championships', 'activeLobbies'));
    }
}