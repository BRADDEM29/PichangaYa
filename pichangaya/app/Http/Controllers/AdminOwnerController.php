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
        $data = $request->validate([
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

        // 3. Sincronizar Servicios (ESTO FALTABA)
        if ($request->has('services')) {
            // Asegúrate de que en el modelo Cancha tengas la relación public function services()
            $cancha->services()->sync($request->services);
        }

        // 4. Eliminar Imágenes marcadas (ESTO FALTABA)
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $mediaId) {
                // Buscamos la imagen en la colección de esta cancha y la borramos
                $cancha->getMedia('canchas')->where('id', $mediaId)->first()?->delete();
            }
        }

        // 5. Subir Nuevas Imágenes (ESTO FALTABA)
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