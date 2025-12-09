<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Sport;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CanchaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Muestra el listado de canchas del dueño.
     */
    public function index()
    {
        $this->authorize('viewAny', Cancha::class);

        $canchas = Cancha::where('user_id', Auth::id())
                    ->with(['media', 'sports', 'district'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('owner.canchas.index', compact('canchas'));
    }

    /**
     * Formulario para crear.
     */
    public function create()
    {
        $this->authorize('create', Cancha::class);

        $sports = Sport::all();
        $districts = District::all();
        
        $user = Auth::user();
        $phones = collect([
            ['number' => $user->phone, 'label' => 'Principal (' . $user->name . ')']
        ]);
        
        if ($user->secondaryPhones) {
            foreach($user->secondaryPhones as $p) {
                $phones->push(['number' => $p->phone_number, 'label' => $p->label]);
            }
        }

        return view('owner.canchas.create', compact('sports', 'districts', 'phones'));
    }

    /**
     * Guarda la nueva cancha.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cancha::class);

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
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:20480',
            'sports'         => 'required|array|min:1',
            'sports.*'       => 'exists:sports,id',
        ]);

        // 1. Crear Cancha 
        $cancha = Auth::user()->canchas()->create($request->except(['images', 'sports']));

        // 2. Guardar Deportes
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
        $this->authorize('update', $cancha);
        
        $sports = Sport::all();
        $districts = District::all();
        
        $user = Auth::user();
        $phones = collect([
            ['number' => $user->phone, 'label' => 'Principal (' . $user->name . ')']
        ]);
        
        if ($user->secondaryPhones) {
            foreach($user->secondaryPhones as $p) {
                $phones->push(['number' => $p->phone_number, 'label' => $p->label ?? 'Secundario']);
            }
        }
        
        return view('owner.canchas.edit', compact('cancha', 'sports', 'districts', 'phones'));
    }

    /**
     * Actualiza la cancha.
     */
    public function update(Request $request, Cancha $cancha)
    {
        $this->authorize('update', $cancha);

        // 1. Validación (Mejorada con 5MB límite)
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
            'sports'         => 'required|array|min:1',
            'sports.*'       => 'exists:sports,id',
            
            // Validación de imágenes (Opcional en update, permitimos arrays vacíos)
            'images'         => 'nullable|array|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'delete_images'  => 'nullable|array',
        ]);

        // 2. Actualizar Datos Básicos
        $cancha->update($request->except(['images', 'delete_images', 'sports']));
        
        // 3. Sincronizar Deportes
        $cancha->sports()->sync($request->sports);
        
        // 4. Eliminar Imágenes Marcadas (Si el usuario seleccionó alguna)
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $mediaId) {
                $media = $cancha->media()->find($mediaId);
                if ($media) $media->delete();
            }
        }

        // 5. Agregar NUEVAS Imágenes (Se añaden a la colección, no reemplazan)
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
        $this->authorize('delete', $cancha);

        $cancha->delete();
        return redirect()->route('owner.canchas.index')->with('success', 'Cancha eliminada.');
    }
}