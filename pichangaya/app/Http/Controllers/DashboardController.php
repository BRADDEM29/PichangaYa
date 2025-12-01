<?php

namespace App\Http\Controllers;

use App\Models\Cancha; // <--- CAMBIO: Usamos el modelo de tu compañero
use App\Models\District;
use App\Models\Sport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Usamos 'Cancha' y cargamos las relaciones (incluyendo 'media' para las fotos)
        $query = Cancha::with(['district', 'sport', 'media']); 

        // 1. Filtro Texto (Nombre o Dirección)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 2. Filtro Distrito
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->input('district_id'));
        }

        // 3. Filtro Deporte
        if ($request->filled('sport_id')) {
            $query->where('sport_id', $request->input('sport_id'));
        }

        $canchas = $query->get(); // Cambiamos variable a $canchas
        $districts = District::all();
        $sports = Sport::all();

        // Enviamos 'canchas' a la vista en lugar de 'businesses'
        return view('dashboard', compact('canchas', 'districts', 'sports'));
    }
}