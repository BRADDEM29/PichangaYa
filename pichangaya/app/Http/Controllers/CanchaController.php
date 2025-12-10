<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Sport;
use App\Models\District;
use App\Models\Service;
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
        $services = Service::all();
        
        $user = Auth::user();
        $phones = collect([
            ['number' => $user->phone, 'label' => 'Principal (' . $user->name . ')']
        ]);
        
        if ($user->secondaryPhones) {
            foreach($user->secondaryPhones as $p) {
                $phones->push(['number' => $p->phone_number, 'label' => $p->label]);
            }
        }

        return view('owner.canchas.create', compact('sports', 'districts', 'phones', 'services'));
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
            'services'       => 'nullable|array',
            'services.*'     => 'exists:services,id',
        ]);

        // 1. Crear Cancha
        $cancha = Auth::user()->canchas()->create($request->except(['images', 'sports', 'services']));

        // 2. Guardar Deportes
        $cancha->sports()->sync($request->sports);

        // 3. Guardar Servicios
        $cancha->services()->sync($request->input('services', []));

        // 4. Guardar Imágenes
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
        $services = Service::all();
        
        $user = Auth::user();
        $phones = collect([
            ['number' => $user->phone, 'label' => 'Principal (' . $user->name . ')']
        ]);
        
        if ($user->secondaryPhones) {
            foreach($user->secondaryPhones as $p) {
                $phones->push(['number' => $p->phone_number, 'label' => $p->label ?? 'Secundario']);
            }
        }
        
        return view('owner.canchas.edit', compact('cancha', 'sports', 'districts', 'phones', 'services'));
    }

    /**
     * Actualiza la cancha.
     */
    public function update(Request $request, Cancha $cancha)
    {
        $this->authorize('update', $cancha);

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
            'services'       => 'nullable|array',
            'services.*'     => 'exists:services,id',
            
            'images'         => 'nullable|array|max:10', 
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:20480',
            'delete_images'  => 'nullable|array',
        ]);

        // 2. Actualizar Datos Básicos
        $cancha->update($request->except(['images', 'delete_images', 'sports', 'services']));
        
        // 3. Sincronizar Deportes
        $cancha->sports()->sync($request->sports);

        // 4. Sincronizar Servicios
        $cancha->services()->sync($request->input('services', []));
        
        // 5. Eliminar Imágenes Marcadas
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $mediaId) {
                $media = $cancha->media()->find($mediaId);
                if ($media) $media->delete();
            }
        }

        // 6. Agregar NUEVAS Imágenes
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

    /**
     * 🟢 NUEVO: Muestra el historial financiero agrupado por meses.
     */
    public function history(Cancha $cancha)
    {
        // 1. Seguridad: Solo el dueño puede ver esto
        $this->authorize('update', $cancha);

        // 2. Obtener reservas ordenadas por fecha (más recientes primero)
        $reservas = $cancha->reservas()
                           ->with('user')
                           ->orderBy('start_time', 'desc')
                           ->get();

        // 3. Agrupar por "Mes Año" (Ej: "Diciembre 2025")
        // Usamos Carbon para formatear la fecha
        $reservasPorMes = $reservas->groupBy(function($reserva) {
            return \Carbon\Carbon::parse($reserva->start_time)
                    ->locale('es')
                    ->isoFormat('MMMM YYYY'); 
        });

        return view('owner.canchas.history', compact('cancha', 'reservasPorMes'));
    }
}