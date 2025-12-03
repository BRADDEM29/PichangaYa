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
                    ->with(['media', 'sports', 'district']) // 🟢 Cambio: 'sports' en plural
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
        
        // Lógica de Teléfonos (Principal + Secundarios)
        $user = Auth::user();
        $phones = collect([
            ['number' => $user->phone, 'label' => 'Principal (' . $user->name . ')']
        ]);
        foreach($user->secondaryPhones as $p) {
            $phones->push(['number' => $p->phone_number, 'label' => $p->label]);
        }

        return view('owner.canchas.create', compact('sports', 'districts', 'phones'));
    }

    /**
     * Guarda la nueva cancha (Multideporte, Horarios, Mapa, Teléfonos).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'district_id'    => 'required|exists:districts,id',
            'description'    => 'nullable|string|max:1000',
            'lat'            => 'nullable|numeric|between:-90,90',
            'lng'            => 'nullable|numeric|between:-180,180',
            'open_time'      => 'required|date_format:H:i',
            'close_time'     => 'required|date_format:H:i|after:open_time',
            'contact_phone'  => 'required|string|max:20', 
            'images'         => 'required|array|min:1|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            
            // 🟢 CAMBIO: Validamos que 'sports' sea un array con al menos 1 selección
            'sports'         => 'required|array|min:1',
            'sports.*'       => 'exists:sports,id',
        ]);

        // 1. Crear Cancha 
        // Excluimos 'sports' (porque no es columna directa) e 'images'
        $cancha = Auth::user()->canchas()->create($request->except(['images', 'sports']));

        // 2. Guardar Deportes (Llenar tabla intermedia)
        $cancha->sports()->sync($request->sports);

        // 3. Guardar Imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('owner.canchas.index')->with('success', 'Cancha creada exitosamente.');
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
        
        // Lógica de Teléfonos
        $user = Auth::user();
        $phones = collect([
            ['number' => $user->phone, 'label' => 'Principal (' . $user->name . ')']
        ]);
        foreach($user->secondaryPhones as $p) {
            $phones->push(['number' => $p->phone_number, 'label' => $p->label ?? 'Secundario']);
        }
        
        return view('owner.canchas.edit', compact('cancha', 'sports', 'districts', 'phones'));
    }

    /**
     * Actualiza la cancha.
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
            'district_id'    => 'required|exists:districts,id',
            'description'    => 'nullable|string|max:1000',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'open_time'      => 'required|date_format:H:i',
            'close_time'     => 'required|date_format:H:i|after:open_time',
            'contact_phone'  => 'required|string|max:20',
            'images'         => 'nullable|array|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images'  => 'nullable|array',
            
            // 🟢 CAMBIO: Validar array de deportes
            'sports'         => 'required|array|min:1',
            'sports.*'       => 'exists:sports,id',
        ]);

        // 1. Actualizar datos (Excluimos lo que no es columna directa)
        $cancha->update($request->except(['images', 'delete_images', 'sports']));
        
        // 2. Sincronizar Deportes (Actualiza la tabla intermedia automáticamente)
        $cancha->sports()->sync($request->sports);
        
        // 3. Eliminar imágenes marcadas
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $mediaId) {
                $media = $cancha->media()->find($mediaId);
                if ($media) $media->delete();
            }
        }

        // 4. Agregar nuevas imágenes
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