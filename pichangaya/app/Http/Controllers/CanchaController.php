<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Sport;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanchaController extends Controller
{
    /**
     * Muestra el listado de canchas del dueño (Tablero).
     * Ruta: owner.canchas.index
     */
    public function index()
    {
        $canchas = Cancha::where('user_id', Auth::id())
                    ->with(['media', 'sport', 'district']) 
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('owner.canchas.index', compact('canchas'));
    }

    /**
     * Muestra el formulario para crear una nueva cancha.
     * Ruta: owner.canchas.create
     */
    public function create()
    {
        $sports = Sport::all();
        $districts = District::all();
        
        return view('owner.canchas.create', compact('sports', 'districts'));
    }

    /**
     * Guarda la nueva cancha en la base de datos (con múltiples imágenes).
     * Ruta: owner.canchas.store
     */
    public function store(Request $request)
    {
        // 1. Validación
        $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'sport_id'       => 'required|exists:sports,id',
            'district_id'    => 'required|exists:districts,id',
            'description'    => 'nullable|string|max:1000',
            // Validación para array de imágenes
            'images'         => 'required|array|min:1|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        // 2. Crear el registro
        $cancha = Cancha::create([
            'name'           => $request->name,
            'address'        => $request->address,
            'price_per_hour' => $request->price_per_hour,
            'sport_id'       => $request->sport_id,
            'district_id'    => $request->district_id,
            'description'    => $request->description,
            'user_id'        => Auth::id(),
        ]);

        // 3. Procesar las imágenes con Spatie (Bucle para múltiples archivos)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('owner.canchas.index')->with('success', 'Cancha creada exitosamente.');
    }
    
    /**
     * Muestra el formulario de edición.
     * Ruta: owner.canchas.edit
     */
    public function edit(Cancha $cancha)
    {
        if ($cancha->user_id !== Auth::id()) {
            abort(403);
        }
        
        $sports = Sport::all();
        $districts = District::all();
        
        return view('owner.canchas.edit', compact('cancha', 'sports', 'districts'));
    }

    /**
     * Actualiza la cancha.
     * Ruta: owner.canchas.update
     */
    public function update(Request $request, Cancha $cancha)
    {
        if ($cancha->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'sport_id'       => 'required|exists:sports,id',
            'district_id'    => 'required|exists:districts,id',
            'description'    => 'nullable|string|max:1000',
            'images'         => 'nullable|array|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        // Actualizar datos
        $cancha->update($request->only([
            'name', 'address', 'price_per_hour', 'sport_id', 'district_id', 'description',
        ]));
        
        // Manejo de nuevas imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('owner.canchas.index')->with('success', 'Cancha actualizada.');
    }
    
    public function destroy(Cancha $cancha)
    {
        if ($cancha->user_id !== Auth::id()) {
            abort(403);
        }
        $cancha->delete();
        return redirect()->route('owner.canchas.index')->with('success', 'Cancha eliminada.');
    }
}