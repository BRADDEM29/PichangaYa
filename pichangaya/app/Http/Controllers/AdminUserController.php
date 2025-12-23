<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\AdminUserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPhone; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    // 1. LISTAR USUARIOS (CON FILTROS Y BUSCADOR)
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 Lógica del Buscador (Nombre, Email o Teléfono)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 🏷️ Lógica del Filtro por Rol
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // 🟢 Paginación con withQueryString para no perder filtros
        $users = $query->orderBy('id', 'asc')
                       ->paginate(10)
                       ->withQueryString(); 

        return view('admin.users.index', compact('users'));
    }

    // 2. VISTA DE CREACIÓN
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. GUARDAR NUEVO USUARIO
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,owner,user',
            'phone'    => 'required|string|max:20', 
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

        // LÓGICA ESPECIAL PARA OWNERS (Teléfonos Secundarios)
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
            ->with('success', 'Usuario creado exitosamente.');
    }

    // 4. VISTA DE EDICIÓN
    public function edit($id)
    {
        $user = User::with('secondaryPhones')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // 5. ACTUALIZAR USUARIO (CON LÓGICA DE OCULTAR CANCHAS)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // --- SEGURIDAD ---
        if ($user->id === Auth::id()) {
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
            'password' => 'nullable|string|min:8|confirmed',
            'secondary_phones'   => 'nullable|array',
            'secondary_phones.*' => 'nullable|string|max:20',
        ]);

        // 🟢 DETECTAR CAMBIO DE ROL: DE DUEÑO A OTRO (ADMIN/USER)
        $eraDueno = $user->role === 'owner';
        $seraOtro = $request->role !== 'owner';

        // Si deja de ser dueño, OCULTAMOS (DESACTIVAMOS) sus canchas para que nadie reserve
        if ($eraDueno && $seraOtro) {
            // Actualización masiva: Ponemos is_active en false
            $user->canchas()->update(['is_active' => false]);
            
            // Borramos los teléfonos secundarios porque ya no los necesita como usuario normal
            $user->secondaryPhones()->delete();
        }

        // Actualizar datos básicos
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Actualizar teléfonos (SOLO si el rol final es OWNER)
        // Primero borramos los anteriores para evitar duplicados o basura
        $user->secondaryPhones()->delete(); 

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

    // 6. ELIMINAR USUARIO (CON ELIMINACIÓN EN CASCADA DE CANCHAS)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        if ($user->id === 1) {
            return back()->with('error', 'No se puede eliminar al Admin Principal.');
        }

        // 1. Eliminar teléfonos secundarios
        $user->secondaryPhones()->delete();

        // 🟢 NUEVO: Eliminar todas las canchas asociadas a este usuario.
        // Usamos un bucle foreach para asegurar que se ejecuten eventos de modelo (si hubiera)
        // y para mantener limpia la BD.
        foreach ($user->canchas as $cancha) {
            // Opcional: Descomentar si deseas borrar reservas explícitamente
            // $cancha->reservas()->delete(); 
            
            $cancha->delete();
        }

        // Finalmente eliminamos al usuario
        $user->delete();

        return back()->with('success', 'Usuario y sus canchas eliminados correctamente.');
    }

    // 7. BLOQUEAR / DESBLOQUEAR
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id() || $user->id === 1) {
            return back()->with('error', 'Acción no permitida en esta cuenta.');
        }

        $user->is_blocked = !$user->is_blocked;
        
        // Si desbloqueamos, reseteamos los strikes a 0 para darle una nueva oportunidad
        if (!$user->is_blocked) {
            $user->consecutive_cancellations = 0;
        }
        $user->save();

        $status = $user->is_blocked ? 'bloqueado' : 'desbloqueado';
        return back()->with('success', "Usuario $status correctamente.");
    }
    // 8. GESTIONAR STRIKES MANUALMENTE
    public function updateStrikes(Request $request, User $user)
    {
        // Seguridad: Solo admin puede hacer esto
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'strikes' => 'required|integer|min:0|max:10',
        ]);

        $user->consecutive_cancellations = $request->strikes;
        
        // Si le ponemos menos de 4 strikes y estaba bloqueado, podríamos desbloquearlo opcionalmente
        // pero mejor respetamos el bloqueo manual. Solo reseteamos el contador.
        
        $user->save();

        return back()->with('success', "Strikes actualizados a: {$request->strikes}");
    }
}