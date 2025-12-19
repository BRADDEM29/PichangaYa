<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class AdminContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }

    // 🟢 NUEVO: Cambiar estado
    public function updateStatus(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => $request->status]);
        
        return back()->with('success', 'Estado de la consulta actualizado.');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return back()->with('success', 'Consulta eliminada correctamente.');
    }
}