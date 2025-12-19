<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\Admin\SuggestionController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuggestionController extends Controller
{
    /**
     * Muestra la lista de sugerencias enviadas por los usuarios.
     * Solo accesible para administradores (protegido por middleware en web.php).
     */
    public function index()
    {
        // Obtenemos las sugerencias uniendo con la tabla de usuarios para ver quién la envió
        // Usamos paginate para no sobrecargar la vista si hay cientos de mensajes
        $suggestions = DB::table('suggestions')
            ->leftJoin('users', 'suggestions.user_id', '=', 'users.id')
            ->select(
                'suggestions.*', 
                'users.name as user_name', 
                'users.profile_photo_path'
            )
            ->orderBy('suggestions.created_at', 'desc')
            ->paginate(10);

        return view('admin.suggestions.index', compact('suggestions'));
    }

    /**
     * Opcional: Eliminar una sugerencia si es necesario.
     */
    public function destroy($id)
    {
        DB::table('suggestions')->where('id', $id)->delete();

        return redirect()->route('admin.suggestions.received')
            ->with('success', 'Sugerencia eliminada correctamente.');
    }
}