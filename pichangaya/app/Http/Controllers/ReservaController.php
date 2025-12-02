<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    /**
     * Valida si un horario solicitado (start_time, end_time) para una cancha
     * específica está disponible.
     */
    protected function isAvailable(int $canchaId, string $startTime, string $endTime): bool
    {
        $start = new \DateTime($startTime);
        $end = new \DateTime($endTime);

        $existingReserva = Reserva::where('cancha_id', $canchaId)
            ->where('end_time', '>', $start)
            ->where('start_time', '<', $end)
            ->whereNotIn('status', ['cancelled']) 
            ->exists();
            
        return !$existingReserva;
    }

    /**
     * Endpoint para crear una nueva reserva.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'total_price' => 'required|numeric|min:0.01',
        ]);

        $canchaId = $request->cancha_id;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        if (!$this->isAvailable($canchaId, $startTime, $endTime)) {
            throw ValidationException::withMessages([
                'availability' => ['El horario solicitado se superpone con una reserva existente. Por favor, elige otro horario.'],
            ]);
        }

        $reserva = Reserva::create([
            'cancha_id' => $canchaId,
            'user_id' => Auth::id(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $request->total_price,
            'status' => 'confirmed', 
        ]);

        return response()->json([
            'message' => 'Reserva creada exitosamente.',
            'reserva' => $reserva->load('cancha', 'user'),
        ], 201);
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
        
        // Apuntamos a la carpeta correcta: resources/views/reservas/user-reservas-index.blade.php
        return view('reservas.user-reservas-index', compact('reservas'));
    }

    /**
     * Muestra el listado de reservas para las canchas propiedad del dueño.
     */
    public function ownerReservasIndex()
    {
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

        if ($reserva->cancha->owner_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para gestionar esta reserva.');
        }

        $reserva->update([
            'status' => $request->status,
        ]);
        
        return back()->with('success', 'Estado actualizado correctamente.');
    }

    // -------------------------------------------------------------------------
    // 🟢 NUEVOS MÉTODOS CRUD (CANCELAR Y EDITAR POR EL USUARIO)
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

        // Retornamos la vista de edición (Asegúrate de crear resources/views/reservas/edit.blade.php)
        return view('reservas.edit', compact('reserva'));
    }
}