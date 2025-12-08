<?php

namespace App\Http\Controllers;

use App\Models\Cancha; 
use App\Models\District;
use App\Models\Sport;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // 🟢 IMPORTANTE: Importar la fachada Cache

class DashboardController extends Controller
{
    /**
     * Muestra la lista de canchas con filtros para usuarios autenticados (Ruta: /dashboard).
     */
    public function index(Request $request)
    {
        // Reutiliza la lógica optimizada
        $data = $this->getCanchasData($request);

        return view('dashboard', $data);
    }

    /**
     * Muestra la lista de canchas con filtros para usuarios NO autenticados (Ruta: /).
     */
    public function welcome(Request $request)
    {
        // Reutiliza la misma lógica optimizada
        $data = $this->getCanchasData($request);

        return view('welcome', $data);
    }

    /**
     * Lógica compartida para obtener canchas, distritos y deportes.
     * 🟢 AQUI APLICAMOS LA OPTIMIZACIÓN Y EL CACHE
     */
    protected function getCanchasData(Request $request): array
    {
        // 1. Cargar relaciones con Eager Loading
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

        // 4. Filtro Deporte (Usando whereHas para muchos a muchos)
        if ($request->filled('sport_id')) {
            $query->whereHas('sports', function($q) use ($request) {
                $q->where('sports.id', $request->input('sport_id'));
            });
        }

        // Obtenemos las canchas (Dinámico, no se cachea por los filtros)
        $canchas = $query->get();

        // 🟢 OPTIMIZACIÓN DE CACHE: Distritos y Deportes
        // Guardamos estas listas en memoria por 1 hora (3600 seg) porque casi nunca cambian.
        // Esto evita hacer 2 consultas a la base de datos cada vez que alguien entra a la página.
        
        $districts = Cache::remember('all_districts', 3600, function () {
            return District::all();
        });

        $sports = Cache::remember('all_sports', 3600, function () {
            return Sport::all();
        });

        return compact('canchas', 'districts', 'sports');
    }

    /**
     * Muestra el detalle de una cancha pública (Ruta: /canchas/{cancha}).
     */
    public function show(Cancha $cancha)
    {
        // Cargar relaciones necesarias
        $cancha->load(['district', 'sports', 'media', 'user']); 

        // Obtener las reservas ocupadas
        $reservasOcupadas = Reserva::where('cancha_id', $cancha->id)
                                   ->whereIn('status', ['confirmed', 'pending']) 
                                   ->where('end_time', '>', now())
                                   ->select('start_time', 'end_time')
                                   ->get();

        return view('canchas.show', compact('cancha', 'reservasOcupadas'));
    }
}