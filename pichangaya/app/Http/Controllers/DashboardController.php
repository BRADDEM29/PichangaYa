<?php

namespace App\Http\Controllers;

use App\Models\Cancha; 
use App\Models\District;
use App\Models\Sport;
use App\Models\Reserva; // NECESARIO para obtener los horarios ocupados
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra la lista de canchas con filtros (Dashboard principal del cliente).
     */
    public function index(Request $request)
    {
        // 1. Cargar relaciones
        // 🔴 CORRECCIÓN: 'sports' en plural para la relación Muchos a Muchos
        $query = Cancha::with(['district', 'sports', 'media']); 

        // 2. Filtro Texto (Nombre o Dirección)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 3. Filtro Distrito
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->input('district_id'));
        }

        // 4. Filtro Deporte (CORRECCIÓN IMPORTANTE)
        // Usamos whereHas para buscar dentro de la tabla intermedia 'cancha_sport'
        if ($request->filled('sport_id')) {
            $query->whereHas('sports', function($q) use ($request) {
                $q->where('sports.id', $request->input('sport_id'));
            });
        }

        $canchas = $query->get(); // Obtenemos las canchas filtradas
        $districts = District::all();
        $sports = Sport::all();

        // Enviamos 'canchas' a la vista
        return view('dashboard', compact('canchas', 'districts', 'sports'));
    }

    /**
     * Muestra el detalle de una cancha pública (Ruta: /canchas/{cancha}).
     * @param \App\Models\Cancha $cancha
     * @return \Illuminate\View\View
     */
    public function show(Cancha $cancha)
    {
        // 1. Cargar la Cancha con sus relaciones necesarias
        // 🔴 CORRECCIÓN: 'sports' en plural
        $cancha->load(['district', 'sports', 'media', 'user']); // Agregué 'user' para el teléfono del dueño

        // 2. Obtener las reservas confirmadas (o pendientes, si aplica) para esta cancha.
        // Esto es crucial para mostrar un calendario de disponibilidad y evitar choques.
        $reservasOcupadas = Reserva::where('cancha_id', $cancha->id)
                                    // Solo las reservas confirmadas o pendientes, no las canceladas.
                                    ->whereIn('status', ['confirmed', 'pending']) 
                                    // Solo las reservas futuras (o que no han terminado aún)
                                    ->where('end_time', '>', now())
                                    ->select('start_time', 'end_time')
                                    ->get();

        // La vista será 'canchas.show' (ej. resources/views/canchas/show.blade.php)
        return view('canchas.show', compact('cancha', 'reservasOcupadas'));
    }
}