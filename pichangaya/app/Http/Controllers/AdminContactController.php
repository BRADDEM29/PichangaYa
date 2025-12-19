<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\Admin\AdminContactController.php
namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; // Necesario para extender de Controller
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    /**
     * Muestra la lista de consultas enviadas por los usuarios.
     */
    public function index()
    {
        // Obtenemos las consultas ordenadas por la más reciente y paginadas de 10 en 10
        $contacts = Contact::latest()->paginate(10);

        // Retornamos la vista (asegúrate de que el archivo exista en resources/views/admin/contacts/index.blade.php)
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Elimina una consulta de la base de datos.
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        // Regresamos a la lista con un mensaje de éxito (usando el banner de Jetstream)
        return back()->with('flash.banner', 'La consulta ha sido eliminada permanentemente.');
    }
}