<?php

namespace App\Http\Controllers;

use App\Models\Cancha; 
use App\Models\District;
use App\Models\Sport;
use App\Models\Reserva;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra la lista de canchas con filtros para usuarios autenticados (Ruta: /dashboard).
     */
    public function index(Request $request)
    {
        // Reutiliza la lógica del método getCanchasData para cargar los datos
        $data = $this->getCanchasData($request);

        // Envía los datos a la vista del dashboard para usuarios logueados
        return view('dashboard', $data);
    }

    /**
     * Muestra la lista de canchas con filtros para usuarios NO autenticados (Ruta: /).
     */
    public function welcome(Request $request)
    {
        // Reutiliza la misma lógica de carga de datos
        $data = $this->getCanchasData($request);

        // Envía los datos a la vista pública 'welcome'
        return view('welcome', $data);
    }

    /**
     * Lógica compartida para obtener canchas, distritos y deportes según los filtros.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getCanchasData(Request $request): array
    {
        // 1. Cargar relaciones: 'district', 'sports', 'media'
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

        // 4. Filtro Deporte (Usando whereHas)
        if ($request->filled('sport_id')) {
            $query->whereHas('sports', function($q) use ($request) {
                $q->where('sports.id', $request->input('sport_id'));
            });
        }

        $canchas = $query->get();
        $districts = District::all();
        $sports = Sport::all();

        return compact('canchas', 'districts', 'sports');
    }

    /**
     * Muestra el detalle de una cancha pública (Ruta: /canchas/{cancha}).
     */
    public function show(Cancha $cancha)
    {
        // Cargar relaciones necesarias
        $cancha->load(['district', 'sports', 'media', 'user']); 

        // Obtener las reservas ocupadas (confirmadas o pendientes)
        $reservasOcupadas = Reserva::where('cancha_id', $cancha->id)
                                 ->whereIn('status', ['confirmed', 'pending']) 
                                 ->where('end_time', '>', now())
                                 ->select('start_time', 'end_time')
                                 ->get();

        return view('canchas.show', compact('cancha', 'reservasOcupadas'));
    }
}