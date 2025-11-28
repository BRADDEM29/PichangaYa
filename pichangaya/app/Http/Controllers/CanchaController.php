<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Sport;      // Modelo en Inglés
use App\Models\District;   // Modelo en Inglés
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanchaController extends Controller
{
    public function index()
    {
        // Esto lo usaremos luego para listar las canchas
    }

    // Muestra el formulario
    public function create()
    {
        $sports = Sport::all();       // Trae los deportes
        $districts = District::all(); // Trae los distritos
        
        return view('owner.canchas.create', compact('sports', 'districts'));
    }

    // Guarda los datos
    public function store(Request $request)
    {
        // 1. Validar (Nombres en inglés)
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric',
            'sport_id' => 'required|exists:sports,id',      // Validación corregida
            'district_id' => 'required|exists:districts,id', // Validación corregida
            'description' => 'nullable|string',
        ]);

        // 2. Guardar
        Auth::user()->canchas()->create([
            'name' => $request->name,
            'address' => $request->address,
            'price_per_hour' => $request->price_per_hour,
            'sport_id' => $request->sport_id,       // Columna en inglés
            'district_id' => $request->district_id, // Columna en inglés
            'description' => $request->description,
        ]);

        return redirect()->route('owner.dashboard')->with('success', '¡Cancha registrada con éxito!');
    }
}