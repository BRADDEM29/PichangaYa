<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    // 1. Mostrar la lista de usuarios
    public function index()
    {
        // Traemos todos los usuarios de la base de datos
        $users = User::all(); 
        return view('admin.users.index', compact('users'));
    }

    // 2. Actualizar el rol de un usuario
   public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // --- SEGURIDAD 1: ANTI-SUICIDIO ---
        // Evita que te quites el admin a ti mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', '¡No puedes cambiar tu propio rol! Te quedarías fuera del sistema.');
        }

        // --- SEGURIDAD 2: PROTECCIÓN DEL SUPER ADMIN (ID 1) ---
        // Asumimos que el usuario con ID 1 es el creador del sistema (Tú).
        // Nadie, ni siquiera otro admin, debería poder degradarlo.
        if ($user->id === 1) {
            return back()->with('error', 'El Administrador Principal (ID 1) es intocable.');
        }

        // Validación normal
        $request->validate([
            'role' => 'required|in:admin,owner,user',
        ]);

        $user->role = $request->input('role');
        $user->save();

        return back()->with('success', 'Rol actualizado correctamente.');
    }
    // 3. Eliminar usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // REGLA 1: No puedes eliminarte a ti mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás conectado.');
        }

        // REGLA 2: No puedes eliminar al Super Admin (ID 1 o por email)
        if ($user->id === 1 || $user->email === 'admin@yanakatari.com') {
            return back()->with('error', 'Este usuario es el Administrador Principal y no puede ser eliminado.');
        }

        // Si pasa las reglas, procedemos
        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}