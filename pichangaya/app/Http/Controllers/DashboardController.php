<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\District;
use App\Models\Sport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Traemos distrito Y deporte para mostrar el ícono en la tarjeta
        $query = Business::with(['district', 'sport']); 

        // 1. Filtro Texto
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

        // --- 3. NUEVO: FILTRO DEPORTE (HU5.1) ---
        if ($request->filled('sport_id')) {
            $query->where('sport_id', $request->input('sport_id'));
        }
        // ----------------------------------------

        $businesses = $query->get();
        $districts = District::all();
        $sports = Sport::all(); // Esto ya lo tenías, ahora sí lo usaremos

        return view('dashboard', compact('businesses', 'districts', 'sports'));
    }
}