<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class AdminDistrictController extends Controller
{
    // 1. Mostrar lista y formulario
    public function index()
    {
        $districts = District::all();
        return view('admin.districts.index', compact('districts'));
    }

    // 2. Guardar nuevo distrito
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:districts|max:255'
        ]);

        District::create($request->all());

        return back()->with('success', 'Distrito creado correctamente.');
    }

    // 3. Actualizar distrito
    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:districts,name,' . $district->id . '|max:255'
        ]);

        $district->update($request->all());

        return back()->with('success', 'Distrito actualizado.');
    }

    // 4. Eliminar distrito
    public function destroy($id)
    {
        $district = District::findOrFail($id);
        $district->delete();

        return back()->with('success', 'Distrito eliminado.');
    }
}