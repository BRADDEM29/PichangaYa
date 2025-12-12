<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cancha;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Estadísticas Generales (KPIs)
        $totalUsers = User::count();
        $totalCanchas = Cancha::count();
        
        // Ingresos Totales (Solo reservas confirmadas o completadas)
        // Asumiendo que 'confirmed' y 'pending' cuentan para la proyección, 
        // pero lo ideal es solo 'confirmed'. Ajusta según tu lógica de negocio.
        $ingresosTotales = Reserva::where('status', '!=', 'cancelled')->sum('total_price');
        $reservasTotales = Reserva::count();

        // 2. Desglose de Usuarios
        $usersByRole = [
            'admins' => User::where('role', 'admin')->count(),
            'owners' => User::where('role', 'owner')->count(),
            'users'  => User::where('role', 'user')->count(),
        ];

        // 3. Reservas Recientes (Últimas 5)
        $recentReservas = Reserva::with(['cancha', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Canchas más Populares (Top 5 con más reservas)
        $topCanchas = Cancha::withCount('reservas')
            ->orderBy('reservas_count', 'desc')
            ->take(5)
            ->get();

        // 5. Datos para Gráfico simple (Reservas últimos 7 días)
        $reservasPorDia = Reserva::select(DB::raw('DATE(start_time) as date'), DB::raw('count(*) as count'))
            ->where('start_time', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalCanchas', 
            'ingresosTotales', 
            'reservasTotales',
            'usersByRole',
            'recentReservas',
            'topCanchas',
            'reservasPorDia'
        ));
    }
}