<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\AdminOwnerController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cancha;
use App\Models\District;
use App\Models\Sport;
use App\Models\Service;
use App\Models\Media; // Importante para subir imágenes
use Illuminate\Http\Request;

class AdminOwnerController extends Controller
{
    // Listar todos los dueños
    public function index()
    {
        $owners = User::where('role', 'owner')->paginate(10);
        return view('admin.owners.index', compact('owners'));
    }

    // 🟢 VER LAS CANCHAS DE UN DUEÑO (Esta es la que daba error)
    public function courts(User $user)
    {
        if ($user->role !== 'owner') {
            return back()->with('error', 'Usuario incorrecto.');
        }
        $canchas = Cancha::where('user_id', $user->id)->with('district')->get();
        return view('admin.owners.courts', compact('user', 'canchas'));
    }

    // Destacar / Quitar destacado
    public function toggleFeatured(Cancha $cancha)
    {
        $cancha->update(['is_featured' => !$cancha->is_featured]);
        return back()->with('success', 'Estado destacado actualizado.');
    }

    // Formulario Crear Cancha
    public function createCancha(User $user)
    {
        $districts = District::all();
        $sports = Sport::all();
        $services = Service::all();
        
        return view('admin.owners.create-cancha', [
            'owner' => $user, 
            'districts' => $districts, 
            'sports' => $sports, 
            'services' => $services
        ]);
    }

    // Guardar Cancha
    public function storeCancha(Request $request, User $user)
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
            'services' => 'array',
            'images.*' => 'image|max:5120'
        ]);

        $cancha = Cancha::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'district_id' => $request->district_id,
            'price_per_hour' => $request->price_per_hour,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'address' => $request->address,
            'lat' => $request->lat ?? 0, 
            'lng' => $request->lng ?? 0,
            'contact_phone' => $request->contact_phone,
            'description' => $request->description,
        ]);

        if ($request->has('sports')) {
            $cancha->sports()->sync($request->sports);
        }
        if ($request->has('services')) {
            $cancha->services()->sync($request->services);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('admin.owners.courts', $user)
            ->with('success', 'Cancha creada exitosamente para ' . $user->name);
    }

    // Formulario Editar Cancha
    public function editCancha(Cancha $cancha)
    {
        $districts = District::all();
        $sports = Sport::all();
        $services = Service::all();
        
        return view('admin.owners.edit-cancha', compact('cancha', 'districts', 'sports', 'services'));
    }

    // Actualizar Cancha
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
            'services' => 'array',
            'images.*' => 'image|max:5120',
            'delete_images' => 'array'
        ]);

        $cancha->update($request->except(['sports', 'services', 'images', 'delete_images']));

        if ($request->has('sports')) {
            $cancha->sports()->sync($request->sports);
        }

        if ($request->has('services')) {
            $cancha->services()->sync($request->services);
        }

        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $mediaId) {
                $cancha->getMedia('canchas')->where('id', $mediaId)->first()?->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $cancha->addMedia($image)->toMediaCollection('canchas');
            }
        }

        return redirect()->route('admin.owners.courts', $cancha->user_id)
            ->with('success', 'Cancha actualizada correctamente.');
    }

    // Eliminar Cancha
    public function destroy(Cancha $cancha)
    {
        $cancha->delete();
        return back()->with('success', 'La cancha ha sido eliminada correctamente.');
    }

    // Ver Reservas de Cancha
    public function canchaReservas(Cancha $cancha)
    {
        $reservas = $cancha->reservas()->with('user')->orderBy('start_time', 'desc')->paginate(10);
        return view('admin.reservas.index', compact('cancha', 'reservas'));
    }
}