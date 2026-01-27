<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use Illuminate\Http\Request;

class AdminSportController extends Controller
{
    /**
     * Muestra la lista de deportes y pasa los iconos disponibles a la vista.
     */
    public function index()
    {
        // 1. Obtener todos los deportes creados
        $sports = Sport::all();

        // 2. DEFINIR LOS ICONOS SVG DISPONIBLES
        // Se definen aquí para pasarlos a la vista y evitar errores de variable no definida.
        $availableIcons = [
            'futbol' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7l4.76 3.45l-1.76 5.55h-6l-1.76 -5.55l4.76 -3.45"></path><path d="M12 7v-4"></path><path d="M16.76 10.45l3.63 -2.11"></path><path d="M15 16l3.3 3.3"></path><path d="M9 16l-3.3 3.3"></path><path d="M7.24 10.45l-3.63 -2.11"></path></svg>',
            
            'basket' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M5.65 5.65l12.7 12.7"></path><path d="M5.65 18.35l12.7 -12.7"></path><path d="M12 3a9 9 0 0 0 9 9"></path><path d="M12 21a9 9 0 0 0 -9 -9"></path></svg>',
            
            'voley'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 12a8 8 0 0 0 8 4"></path><path d="M7.5 13.5a12 12 0 0 0 8.5 6.5"></path><path d="M12 12a8 8 0 0 0 -7.4 -3.6"></path><path d="M12 12a8 8 0 0 0 .5 -7.4"></path></svg>',
            
            'tenis'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M6 5.3a9 9 0 0 1 0 13.4"></path><path d="M18 5.3a9 9 0 0 0 0 13.4"></path></svg>',
            
            'rugby'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c-5.523 0 -10 4.03 -10 9s4.477 9 10 9s10 -4.03 10 -9s-4.477 -9 -10 -9z"></path><path d="M8 7v10"></path><path d="M12 7v10"></path><path d="M16 7v10"></path></svg>',
            
            'baseball'=> '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M5.5 8.5a11 11 0 0 0 13 7"></path><path d="M5.5 15.5a11 11 0 0 1 13 -7"></path></svg>',
            
            'fronton'=> '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21h16"></path><path d="M12 18v3"></path><path d="M12 14a4 4 0 1 0 0 -8 4 4 0 0 0 0 8z"></path><path d="M12 5v-2"></path><path d="M15.5 7.5l2 -2"></path><path d="M8.5 7.5l-2 -2"></path></svg>',

            'pingpong' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 12h-7a3 3 0 0 0 -3 3v3a3 3 0 0 0 3 3h7"></path><path d="M14 12v9"></path><circle cx="15" cy="8" r="4"></circle></svg>'
        ];

        // 3. RETORNAR VISTA CON DATOS
        return view('admin.sports.index', compact('sports', 'availableIcons'));
    }

    /**
     * Guarda un nuevo deporte en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sports|max:255',
            'icon' => 'nullable|string|max:50',
            'total_players' => 'required|integer|min:2|max:60', // Validación correcta de jugadores
        ]);

        Sport::create($request->all());

        return back()->with('success', 'Deporte creado correctamente.');
    }

    /**
     * Actualiza un deporte existente.
     */
    public function update(Request $request, $id)
    {
        $sport = Sport::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:sports,name,' . $sport->id . '|max:255',
            'icon' => 'nullable|string|max:50',
            'total_players' => 'required|integer|min:2|max:60', // Validación correcta de jugadores
        ]);

        $sport->update($request->all());

        return back()->with('success', 'Deporte actualizado.');
    }

    /**
     * Elimina un deporte, verificando dependencias.
     */
    public function destroy($id)
    {
        $sport = Sport::findOrFail($id);
        
        // Verificar si tiene salas activas antes de borrar para evitar errores de integridad
        if($sport->lobbies()->count() > 0) {
            return back()->with('error', 'No se puede eliminar porque hay salas activas usando este deporte.');
        }

        $sport->delete();

        return back()->with('success', 'Deporte eliminado.');
    }
}