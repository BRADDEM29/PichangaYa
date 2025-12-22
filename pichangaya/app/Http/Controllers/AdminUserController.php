<?php
// Ubicación: C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\AdminUserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    // 1. Mostrar la lista de usuarios
    public function index()
    {
        // 🟢 CAMBIO IMPORTANTE: Usamos paginate(10) en lugar de all()
        // para que funcionen los botones de "Siguiente/Anterior" en la vista.
        $users = User::paginate(10); 
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

    // 4. 🟢 NUEVO MÉTODO: BLOQUEAR / DESBLOQUEAR USUARIO
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        // REGLA 1: No puedes bloquearte a ti mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes bloquear tu propia cuenta.');
        }

        // REGLA 2: No puedes bloquear al Super Admin
        if ($user->id === 1) {
            return back()->with('error', 'No puedes bloquear al Administrador Principal.');
        }

        // Invertir el estado de bloqueo (Si es true pasa a false, y viceversa)
        $user->is_blocked = !$user->is_blocked;

        // Si estamos DESBLOQUEANDO, reiniciamos sus "strikes" (cancelaciones) a 0
        // para darle una nueva oportunidad limpia.
        if (!$user->is_blocked) {
            $user->consecutive_cancellations = 0;
        }

        $user->save();

        $estado = $user->is_blocked ? 'bloqueado' : 'desbloqueado';
        return back()->with('success', "El usuario ha sido {$estado} correctamente.");
    }
}