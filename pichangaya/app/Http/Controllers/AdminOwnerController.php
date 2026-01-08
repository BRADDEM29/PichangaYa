<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cancha;
use App\Models\District;
use App\Models\Sport;
use App\Models\Service;
use App\Models\Media; 
use Illuminate\Http\Request;
// 🟢 NUEVOS IMPORTS NECESARIOS
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Str;

class AdminOwnerController extends Controller
{
    // Listar todos los dueños
    public function index()
    {
        $owners = User::where('role', 'owner')->paginate(10);
        return view('admin.owners.index', compact('owners'));
    }

    // Ver las canchas de un dueño
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
            // 🟢 VALIDACIÓN ESTRICTA: Solo jpg, png, jpeg, webp
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120'
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

        // 🟢 PROCESAMIENTO Y CONVERSIÓN A WEBP
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // 1. Generar nombre único .webp
                $filename = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.webp';
                $tempPath = sys_get_temp_dir() . '/' . $filename;

                // 2. Optimizar: Redimensionar (max 1920px) y Convertir
                Image::make($image)
                    ->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('webp', 85) // Calidad 85%
                    ->save($tempPath);

                // 3. Guardar en Spatie (El "original" ahora es WebP ligero)
                $cancha->addMedia($tempPath)
                       ->toMediaCollection('canchas');
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
            // 🟢 VALIDACIÓN ESTRICTA
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
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

        // 🟢 PROCESAMIENTO Y CONVERSIÓN A WEBP
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.webp';
                $tempPath = sys_get_temp_dir() . '/' . $filename;

                Image::make($image)
                    ->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('webp', 85)
                    ->save($tempPath);

                $cancha->addMedia($tempPath)
                       ->toMediaCollection('canchas');
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

    public function canchaReservasPolling(\App\Models\Cancha $cancha)
    {
        $reservas = $cancha->reservas()->latest()->paginate(10);
        return view('admin.reservas.partials.table-body', compact('reservas'))->render();
    }
}