<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Championship;
use App\Models\Lobby;
// use App\Models\Sport; // Descomentar cuando tengamos el modelo
// use App\Models\District; // Descomentar cuando tengamos el modelo

class ArenaController extends Controller
{
    public function index()
    {
        // 1. Cargar Campeonatos Destacados/Activos
        $championships = Championship::where('status', '!=', 'finished')
                                     ->orderBy('start_date', 'asc')
                                     ->take(6)
                                     ->get();

        // 2. Cargar Lobbys activos (Para el carrusel de recomendados "Lobby Hopper")
        // Solo mostramos lobbys que NO estén llenos y que NO hayan expirado
        $activeLobbies = Lobby::where('status', 'searching')
                              ->where('expires_at', '>', now())
                              ->with(['sport', 'district']) // Asumiendo relaciones
                              ->take(5)
                              ->get();

        return view('arena.index', compact('championships', 'activeLobbies'));
    }
}