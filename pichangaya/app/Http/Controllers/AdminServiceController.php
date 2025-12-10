<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    /**
     * Muestra la lista de servicios.
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Guarda un nuevo servicio.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50', // Emojis
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Servicio creado correctamente.');
    }

    /**
     * Actualiza un servicio existente.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Servicio actualizado.');
    }

    /**
     * Elimina un servicio.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Servicio eliminado.');
    }
}