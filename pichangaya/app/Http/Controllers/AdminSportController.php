<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use Illuminate\Http\Request;

class AdminSportController extends Controller
{
    public function index()
    {
        $sports = Sport::all();
        return view('admin.sports.index', compact('sports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sports|max:255',
            'icon' => 'nullable|string|max:50', // Validamos el emoji/ícono
        ]);

        Sport::create($request->all());

        return back()->with('success', 'Deporte creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $sport = Sport::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:sports,name,' . $sport->id . '|max:255',
            'icon' => 'nullable|string|max:50',
        ]);

        $sport->update($request->all());

        return back()->with('success', 'Deporte actualizado.');
    }

    public function destroy($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->delete();

        return back()->with('success', 'Deporte eliminado.');
    }
}