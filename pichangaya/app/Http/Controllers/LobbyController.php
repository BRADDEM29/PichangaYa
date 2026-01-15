<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LobbyController extends Controller
{
    public function show(Lobby $lobby)
    {
        // Seguridad: Solo ver si eres parte del lobby
        $isMember = $lobby->slots()->where('user_id', Auth::id())->exists();

        if (!$isMember) {
            return redirect()->route('arena.index')->with('error', 'No perteneces a esta sala.');
        }

        // Cargar relaciones necesarias
        $lobby->load(['slots.user', 'sport', 'district']);

        return view('arena.lobby', compact('lobby'));
    }
}