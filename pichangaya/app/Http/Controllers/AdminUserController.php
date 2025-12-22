<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\AdminUserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPhone; // 🟢 Importante: No olvides importar este modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    // 1. LISTAR USUARIOS
    public function index()
    {
        $users = User::paginate(10); 
        return view('admin.users.index', compact('users'));
    }

    // 2. 🟢 VISTA DE CREACIÓN
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. 🟢 GUARDAR NUEVO USUARIO
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,owner,user',
            'phone'    => 'required|string|max:20', // Teléfono principal obligatorio
            // Validamos el array de teléfonos secundarios (solo si es owner)
            'secondary_phones'   => 'nullable|array',
            'secondary_phones.*' => 'nullable|string|max:20',
        ]);

        // Crear Usuario
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'phone'      => $request->phone,
            'is_blocked' => false,
        ]);

        // 🟢 LÓGICA ESPECIAL PARA OWNERS (Teléfonos Secundarios)
        if ($request->role === 'owner' && $request->has('secondary_phones')) {
            foreach ($request->secondary_phones as $phoneNum) {
                if (!empty($phoneNum)) {
                    UserPhone::create([
                        'user_id'      => $user->id,
                        'phone_number' => $phoneNum,
                        'label'        => 'Secundario' // Etiqueta por defecto
                    ]);
                }
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    // 4. 🟢 VISTA DE EDICIÓN
    public function edit($id)
    {
        // Cargamos el usuario con sus teléfonos secundarios para poder mostrarlos en el form
        $user = User::with('secondaryPhones')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // 5. 🟢 ACTUALIZAR USUARIO
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // --- SEGURIDAD ---
        if ($user->id === Auth::id()) {
            // Permitimos editar datos, pero NO el rol ni bloquearse a sí mismo
            if ($request->role !== $user->role) {
                return back()->with('error', 'No puedes cambiar tu propio rol.');
            }
        }
        if ($user->id === 1 && Auth::id() !== 1) {
            return back()->with('error', 'No tienes permisos para editar al Super Admin.');
        }

        // Validación
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:admin,owner,user',
            'phone'    => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed', // Opcional en edición
            'secondary_phones'   => 'nullable|array',
            'secondary_phones.*' => 'nullable|string|max:20',
        ]);

        // Actualizar datos básicos
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;
        $user->phone = $request->phone;

        // Solo actualizar contraseña si se escribió algo nuevo
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 🟢 ACTUALIZAR TELÉFONOS SECUNDARIOS (Estrategia: Borrar todo y Recrear)
        // Esto simplifica la lógica de detectar cuáles se editaron y cuáles son nuevos.
        
        // 1. Borramos los anteriores
        $user->secondaryPhones()->delete();

        // 2. Si sigue siendo Owner, agregamos los que vinieron del formulario
        if ($request->role === 'owner' && $request->has('secondary_phones')) {
            foreach ($request->secondary_phones as $phoneNum) {
                if (!empty($phoneNum)) {
                    UserPhone::create([
                        'user_id'      => $user->id,
                        'phone_number' => $phoneNum,
                        'label'        => 'Secundario'
                    ]);
                }
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // 6. ELIMINAR USUARIO
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        if ($user->id === 1) {
            return back()->with('error', 'No se puede eliminar al Admin Principal.');
        }

        // Borramos teléfonos secundarios primero (aunque onDelete cascade en BD lo haría, es buena práctica)
        $user->secondaryPhones()->delete();
        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    // 7. BLOQUEAR / DESBLOQUEAR
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id() || $user->id === 1) {
            return back()->with('error', 'Acción no permitida en esta cuenta.');
        }

        $user->is_blocked = !$user->is_blocked;
        
        // Si desbloqueamos, reseteamos los strikes a 0
        if (!$user->is_blocked) {
            $user->consecutive_cancellations = 0;
        }
        $user->save();

        $status = $user->is_blocked ? 'bloqueado' : 'desbloqueado';
        return back()->with('success', "Usuario $status correctamente.");
    }
}