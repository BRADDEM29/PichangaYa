<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Sport;      // Modelo en Inglés
use App\Models\District;   // Modelo en Inglés
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanchaController extends Controller
{
    // Listar las canchas
    public function index()
    {
        // CORRECCIÓN:
        // En lugar de usar Auth::user()->canchas (que puede dar null si la relación falta),
        // hacemos la consulta directa a la tabla 'canchas' filtrando por el ID del usuario actual.
        // Esto garantiza que siempre devuelva una Colección (aunque esté vacía) y nunca null.
        $canchas = Cancha::where('user_id', Auth::id())->get(); 
        
        // Retornar la vista 'index' y pasarle los datos
        return view('owner.canchas.index', compact('canchas'));
    }

    // Muestra el formulario de creación
    public function create()
    {
        $sports = Sport::all();       // Trae los deportes
        $districts = District::all(); // Trae los distritos
        
        return view('owner.canchas.create', compact('sports', 'districts'));
    }

    // Guarda los datos de la nueva cancha
    public function store(Request $request)
    {
        // 1. Validar
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric',
            'sport_id' => 'required|exists:sports,id',
            'district_id' => 'required|exists:districts,id',
            'description' => 'nullable|string',
        ]);

        // 2. Guardar
        // CORRECCIÓN: Usamos el método create directamente en el modelo Cancha
        // y agregamos manualmente el 'user_id'. Esto es más robusto y evita errores.
        Cancha::create([
            'name' => $request->name,
            'address' => $request->address,
            'price_per_hour' => $request->price_per_hour,
            'sport_id' => $request->sport_id,
            'district_id' => $request->district_id,
            'description' => $request->description,
            'user_id' => Auth::id(), // Asignamos el dueño actual
        ]);

        // Redireccionamos a la lista de canchas para ver la nueva creación
        return redirect()->route('owner.canchas.index')->with('success', '¡Cancha registrada con éxito!');
    }
}