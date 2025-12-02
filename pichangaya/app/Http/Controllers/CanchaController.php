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
     * Muestra el listado de canchas del dueño.
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
     * Formulario para crear.
     */
    public function create()
    {
        $sports = Sport::all();
        $districts = District::all();
        
        return view('owner.canchas.create', compact('sports', 'districts'));
    }

    /**
     * Guarda la nueva cancha (Incluye horarios, mapa y validación de imágenes).
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
            
            // IMAGEN OBLIGATORIA AL CREAR
            'images'         => 'required|array|min:1|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:2048', 
            
            // MAPA
            'lat'            => 'nullable|numeric|between:-90,90',
            'lng'            => 'nullable|numeric|between:-180,180',

            // NUEVOS HORARIOS
            'open_time'      => 'required|date_format:H:i',
            'close_time'     => 'required|date_format:H:i|after:open_time',
        ]);

        // 2. Crear el registro
        // Usamos except para que 'images' no rompa la creación, pero 'open_time', 'lat', etc. pasen directo.
        $cancha = Auth::user()->canchas()->create($request->except('images'));

        // 3. Procesar las imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('owner.canchas.index')->with('success', 'Cancha creada exitosamente con ubicación y horarios.');
    }
    
    /**
     * Formulario de edición.
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
     * Actualiza la cancha (Permite borrar fotos específicas y actualizar horarios).
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
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            
            // VALIDACIONES NUEVAS
            'open_time'      => 'required|date_format:H:i',
            'close_time'     => 'required|date_format:H:i|after:open_time',
            
            // Imágenes son opcionales al editar (porque ya tiene)
            'images'         => 'nullable|array|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            
            // Array de IDs de imágenes para eliminar
            'delete_images'  => 'nullable|array', 
        ]);

        // 1. Actualizar datos (Excluimos imágenes para manejarlas manual)
        $cancha->update($request->except(['images', 'delete_images']));
        
        // 2. ELIMINAR IMÁGENES SELECCIONADAS
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $mediaId) {
                // Buscamos la imagen asociada a esta cancha
                $media = $cancha->media()->find($mediaId);
                if ($media) {
                    $media->delete(); // Spatie la borra del disco y de la BD
                }
            }
        }

        // 3. AGREGAR NUEVAS IMÁGENES
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('owner.canchas.index')->with('success', 'Cancha actualizada correctamente.');
    }
    
    /**
     * Eliminar cancha.
     */
    public function destroy(Cancha $cancha)
    {
        if ($cancha->user_id !== Auth::id()) {
            abort(403);
        }
        $cancha->delete();
        return redirect()->route('owner.canchas.index')->with('success', 'Cancha eliminada.');
    }
}