<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon; // Usaremos Carbon para manejar fechas más fácil

class ReservaController extends Controller
{
    /**
     * Valida si un horario solicitado está disponible.
     */
    protected function isAvailable(int $canchaId, string $startTime, string $endTime): bool
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $existingReserva = Reserva::where('cancha_id', $canchaId)
            ->where('end_time', '>', $start)
            ->where('start_time', '<', $end)
            ->where('status', '!=', 'cancelled') // Ignoramos las canceladas
            ->exists();
            
        return !$existingReserva;
    }

    // -------------------------------------------------------------------------
    // 🟢 MÉTODO NUEVO: CREATE (Formulario de Reserva)
    // -------------------------------------------------------------------------
    /**
     * Muestra la vista con el formulario para crear la reserva.
     * Recibe la cancha seleccionada desde la ruta.
     */
    public function create(Cancha $cancha)
    {
        // Retorna la vista resources/views/reservas/create.blade.php
        return view('reservas.create', compact('cancha'));
    }

    /**
     * Procesa el formulario y guarda la reserva en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de datos
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'start_time' => 'required|date|after:now',
            'end_time'   => 'required|date|after:start_time',
            // 'total_price' => se calcula abajo por seguridad, aunque puedes recibirlo si prefieres
        ]);

        $cancha = Cancha::findOrFail($request->cancha_id);
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        // 2. Verificar Disponibilidad
        if (!$this->isAvailable($cancha->id, $start, $end)) {
            // Si no hay sitio, regresamos al formulario con un error
            return back()
                ->withInput()
                ->withErrors(['availability' => 'El horario solicitado ya está ocupado. Por favor elige otro.']);
        }

        // 3. Calcular Precio (Más seguro hacerlo en el servidor)
        // Calculamos la diferencia en horas (pueden ser decimales, ej: 1.5 horas)
        $hours = $start->diffInMinutes($end) / 60;
        $totalPrice = round($hours * $cancha->price_per_hour, 2);

        // 4. Crear la Reserva
        Reserva::create([
            'cancha_id'   => $cancha->id,
            'user_id'     => Auth::id(),
            'start_time'  => $start,
            'end_time'    => $end,
            'total_price' => $totalPrice,
            'status'      => 'confirmed', 
        ]);

        // 5. Redireccionar al usuario a "Mis Reservas" con mensaje de éxito
        return redirect()->route('reservas.user.index')
            ->with('success', '¡Reserva creada exitosamente!');
    }

    /**
     * Muestra el listado de reservas hechas por el usuario autenticado (Cliente).
     */
    public function userReservasIndex()
    {
        $reservas = Auth::user()->reservas()
                        ->with('cancha')
                        ->latest()
                        ->paginate(10); 
        
        return view('reservas.user-reservas-index', compact('reservas'));
    }

    /**
     * Muestra el listado de reservas para las canchas propiedad del dueño.
     */
    public function ownerReservasIndex()
    {
        // Obtenemos los IDs de las canchas del dueño actual
        $canchaIds = Cancha::where('owner_id', Auth::id())->pluck('id');

        $reservas = Reserva::whereIn('cancha_id', $canchaIds)
            ->with('user', 'cancha') 
            ->latest()
            ->paginate(10); 

        return view('owner-reservas-index', compact('reservas'));
    }

    /**
     * Permite al dueño actualizar el estado (confirmar/cancelar).
     */
    public function updateStatus(Reserva $reserva, Request $request)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        // Verificar que la cancha pertenezca al dueño logueado
        if ($reserva->cancha->owner_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para gestionar esta reserva.');
        }

        $reserva->update([
            'status' => $request->status,
        ]);
        
        return back()->with('success', 'Estado actualizado correctamente.');
    }

    // -------------------------------------------------------------------------
    // MÉTODOS DE GESTIÓN DE USUARIO (Cancelar / Editar)
    // -------------------------------------------------------------------------

    /**
     * Permite al USUARIO cancelar su propia reserva.
     */
    public function cancelUser(Reserva $reserva)
    {
        // 1. Seguridad: Solo el dueño de la reserva puede cancelar
        if ($reserva->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        // 2. Validación: No se puede cancelar si ya pasó
        if ($reserva->start_time < now()) {
             return back()->with('error', 'No puedes cancelar una reserva que ya pasó.');
        }

        // 3. Actualizar estado
        $reserva->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }

    /**
     * Muestra el formulario de edición para el USUARIO.
     */
    public function editUser(Reserva $reserva)
    {
        // 1. Seguridad
        if ($reserva->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reserva->status === 'cancelled') {
            return back()->with('error', 'No puedes editar una reserva cancelada.');
        }

        if ($reserva->start_time < now()) {
            return back()->with('error', 'No puedes editar una reserva pasada.');
        }

        // Retornamos la vista de edición
        return view('reservas.edit', compact('reserva'));
    }
}