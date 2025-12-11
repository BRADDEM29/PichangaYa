<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cancha;
use App\Models\District;
use App\Models\Sport;
use App\Models\Service; // <--- Asegúrate de importar el modelo Service
use Illuminate\Http\Request;

class AdminOwnerController extends Controller
{
    public function index()
    {
        $owners = User::where('role', 'owner')->paginate(10);
        return view('admin.owners.index', compact('owners'));
    }

    public function courts(User $user)
    {
        // Validamos que sea dueño
        if ($user->role !== 'owner') {
            return back()->with('error', 'Usuario incorrecto.');
        }
        
        // Cambiamos 'owner_id' por 'user_id' que es el nombre real en tu BD
        $canchas = Cancha::where('user_id', $user->id)->with('district')->get();
        return view('admin.owners.courts', compact('user', 'canchas'));
    }

    public function toggleFeatured(Cancha $cancha)
    {
        $cancha->update(['is_featured' => !$cancha->is_featured]);
        return back()->with('success', 'Estado destacado actualizado.');
    }

    // 🟢 NUEVO: Mostrar Formulario de Creación
    public function createCancha(User $user)
    {
        $districts = District::all();
        $sports = Sport::all();
        $services = Service::all();
        
        // Pasamos $user como $owner a la vista para saber a quién le creamos la cancha
        return view('admin.owners.create-cancha', [
            'owner' => $user, 
            'districts' => $districts, 
            'sports' => $sports, 
            'services' => $services
        ]);
    }

    // 🟢 NUEVO: Guardar la Cancha
    public function storeCancha(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'price_per_hour' => 'required|numeric|min:0',
            'open_time' => 'required',
            'close_time' => 'required',
            'address' => 'required|string',
            'lat' => 'required',
            'lng' => 'required',
            'contact_phone' => 'required|string',
            'description' => 'nullable|string',
            'sports' => 'array',
            'services' => 'array',
            'images.*' => 'image|max:2048'
        ]);

        // Crear la cancha asignada al dueño ($user)
        $cancha = Cancha::create([
            'user_id' => $user->id, // Asignamos al dueño que recibimos por ruta
            'name' => $request->name,
            'district_id' => $request->district_id,
            'price_per_hour' => $request->price_per_hour,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'contact_phone' => $request->contact_phone,
            'description' => $request->description,
        ]);

        // Guardar Relaciones
        if ($request->has('sports')) {
            $cancha->sports()->sync($request->sports);
        }
        if ($request->has('services')) {
            $cancha->services()->sync($request->services);
        }

        // Guardar Imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('admin.owners.courts', $user)
            ->with('success', 'Cancha creada exitosamente para ' . $user->name);
    }

    public function editCancha(Cancha $cancha)
    {
        $districts = District::all();
        $sports = Sport::all();
        $services = Service::all(); // <--- Traemos los servicios
        
        return view('admin.owners.edit-cancha', compact('cancha', 'districts', 'sports', 'services'));
    }

    // 🟢 MÉTODO CORREGIDO: AHORA GUARDA TODO (IMÁGENES Y SERVICIOS)
    public function updateCancha(Request $request, Cancha $cancha)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'price_per_hour' => 'required|numeric|min:0',
            'open_time' => 'required',
            'close_time' => 'required',
            'address' => 'required|string',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'contact_phone' => 'required|string',
            'description' => 'nullable|string',
            'sports' => 'array',
            'services' => 'array', // Validar array de servicios
            'images.*' => 'image|max:2048', // Validar imágenes
            'delete_images' => 'array' // Validar array de borrar
        ]);

        // 1. Actualizar datos básicos
        $cancha->update($request->except(['sports', 'services', 'images', 'delete_images']));

        // 2. Sincronizar Deportes
        if ($request->has('sports')) {
            $cancha->sports()->sync($request->sports);
        }

        // 3. Sincronizar Servicios
        if ($request->has('services')) {
            $cancha->services()->sync($request->services);
        }

        // 4. Eliminar Imágenes marcadas
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $mediaId) {
                // Buscamos la imagen en la colección de esta cancha y la borramos
                $cancha->getMedia('canchas')->where('id', $mediaId)->first()?->delete();
            }
        }

        // 5. Subir Nuevas Imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('admin.owners.courts', $cancha->user_id)
            ->with('success', 'Cancha actualizada correctamente con todos los cambios.');
    }

    public function destroy(Cancha $cancha)
    {
        $cancha->delete();
        return back()->with('success', 'La cancha ha sido eliminada correctamente.');
    }
}