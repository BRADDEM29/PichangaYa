<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suggestion; // Usamos el modelo Eloquent ahora

class SuggestionController extends Controller
{
    public function index()
    {
        // Usamos Eloquent con with('user') para optimizar
        $suggestions = Suggestion::with('user')->latest()->paginate(10);
        return view('admin.suggestions.index', compact('suggestions'));
    }

    // 🟢 NUEVO: Cambiar estado
    public function updateStatus(Request $request, $id)
    {
        $suggestion = Suggestion::findOrFail($id);
        $suggestion->update(['status' => $request->status]);
        
        return back()->with('success', 'Estado de la sugerencia actualizado.');
    }

    public function destroy($id)
    {
        $suggestion = Suggestion::findOrFail($id);
        $suggestion->delete();
        return back()->with('success', 'Sugerencia eliminada.');
    }
}