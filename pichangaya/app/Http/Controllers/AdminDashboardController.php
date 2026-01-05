<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\AdminDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Cancha;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    // 🟢 DASHBOARD PRINCIPAL
    public function index()
    {
        // 1. KPIs Generales
        $totalUsers = User::count();
        $totalCanchas = Cancha::count();
        $reservasTotales = Reserva::count();

        // 2. ESTADOS FINANCIEROS (Totales Globales)
        $ingresosTotales = Reserva::where('status', 'fully_paid')->sum('total_price') ?? 0;
        $adelantosTotal  = Reserva::where('status', 'advance_paid')->sum('total_price') ?? 0;
        $pendientesMoney = Reserva::where('status', 'pending')->sum('total_price') ?? 0;
        $canceladosMoney = Reserva::where('status', 'cancelled')->sum('total_price') ?? 0;

        $adelantosCount  = Reserva::where('status', 'advance_paid')->count();
        $pendientesCount = Reserva::where('status', 'pending')->count();
        $canceladosCount = Reserva::where('status', 'cancelled')->count();

        // =========================================================================
        // 3. GRÁFICO TENDENCIA FINANCIERA (DINÁMICO POR RANGO)
        // =========================================================================
        
        // 🟢 NUEVA LÓGICA DE RANGO DE TIEMPO
        $range = request('range', '15d'); // Valor por defecto '15d' si no viene nada en la URL
        
        $days = match($range) {
            'day'   => 1,   // Último día (hoy)
            'week'  => 7,   // Última semana
            '15d'   => 15,  // Últimos 15 días
            'month' => 30,  // Último mes
            'year'  => 365, // Último año
            default => 15
        };

        $fechas = [];
        $dataFullyPaid = [];
        $dataAdvance = [];
        $dataPending = [];
        $dataCancelled = [];

        // Modificar el bucle para usar $days dinámicamente
        // Si es 'year', podrías optimizar agrupar por meses, pero por ahora día a día funciona.
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Formato de etiqueta: Si es rango de 1 día, mostramos horas? Por ahora mantenemos día/mes.
            // Para 'year', mostrar todos los días puede saturar, pero Chart.js lo maneja (maxTicksLimit).
            $fechas[] = $date->format('d/m'); 
            
            $dateString = $date->toDateString();

            // Aseguramos conversión a float para Chart.js
            $dataFullyPaid[] = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'fully_paid')->sum('total_price');
            $dataAdvance[]   = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'advance_paid')->sum('total_price');
            $dataPending[]   = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'pending')->sum('total_price');
            $dataCancelled[] = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'cancelled')->sum('total_price');
        }

        // 4. DATOS USUARIOS
        $rawRoles = User::select('role', DB::raw('count(*) as count'))->groupBy('role')->pluck('count', 'role')->toArray();
        $usersByRole = [
            'users'  => $rawRoles['user'] ?? 0,
            'owners' => $rawRoles['owner'] ?? 0,
            'admins' => $rawRoles['admin'] ?? 0,
        ];

        // 5. Tablas rápidas
        $recentReservas = Reserva::with(['cancha', 'user' => function($q) { 
                $q->withTrashed(); 
            }])
            ->latest()
            ->take(5)
            ->get();

        $topCanchas = Cancha::withCount('reservas')->orderByDesc('reservas_count')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalCanchas', 'reservasTotales', 
            'ingresosTotales', 
            'adelantosTotal', 'adelantosCount', 
            'pendientesCount', 'pendientesMoney',
            'canceladosCount', 'canceladosMoney',
            'usersByRole', 'recentReservas', 'topCanchas',
            'fechas', 'dataFullyPaid', 'dataAdvance', 'dataPending', 'dataCancelled'
        ));
    }

    // --- REPORTES DETALLADOS ---
    
    public function reportsIngresos()
{
    return view('admin.reports.ingresos');
}

    public function reportsAdelantados()
    {
        $adelantados = Reserva::with(['cancha.user', 'user' => function($q){ $q->withTrashed(); }, 'cancha'])
            ->where('status', 'advance_paid')
            ->orderBy('start_time', 'asc')
            ->get();

        $totalAdelanto = $adelantados->sum('total_price');
        $countAdelanto = $adelantados->count();

        $advanceByCancha = Reserva::where('status', 'advance_paid')
            ->join('canchas', 'reservas.cancha_id', '=', 'canchas.id')
            ->select('canchas.name', DB::raw('SUM(reservas.total_price) as total'))
            ->groupBy('canchas.name')
            ->pluck('total', 'canchas.name')
            ->toArray();

        return view('admin.reports.adelantados', compact('adelantados', 'totalAdelanto', 'countAdelanto', 'advanceByCancha'));
    }

    public function reportsPendientes()
    {
        $pendientes = Reserva::with(['cancha.user', 'user' => function($q){ $q->withTrashed(); }, 'cancha'])
            ->where('status', 'pending')
            ->orderBy('start_time', 'asc')
            ->get();
            
        $totalPendiente = $pendientes->sum('total_price');
        $countPendiente = $pendientes->count();
        $pendingByCancha = Reserva::where('status', 'pending')->join('canchas', 'reservas.cancha_id', '=', 'canchas.id')->select('canchas.name', DB::raw('SUM(reservas.total_price) as total'))->groupBy('canchas.name')->pluck('total', 'canchas.name')->toArray();

        return view('admin.reports.pendientes', compact('pendientes', 'totalPendiente', 'countPendiente', 'pendingByCancha'));
    }

    public function reportsCancelados()
    {
        $cancelados = Reserva::with(['cancha.user', 'user' => function($q){ $q->withTrashed(); }, 'cancha'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $totalPerdido = $cancelados->sum('total_price');
        $countCancelados = $cancelados->count();
        $lostByDistrict = Reserva::where('status', 'cancelled')->join('canchas', 'reservas.cancha_id', '=', 'canchas.id')->join('districts', 'canchas.district_id', '=', 'districts.id')->select('districts.name', DB::raw('SUM(reservas.total_price) as total'))->groupBy('districts.name')->pluck('total', 'districts.name')->toArray();

        return view('admin.reports.cancelados', compact('cancelados', 'totalPerdido', 'countCancelados', 'lostByDistrict'));
    }

    public function reportsReservas() {
        $reservasStatus = Reserva::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status')->toArray();
        $reservasStatus = array_merge(['fully_paid' => 0, 'pending' => 0, 'cancelled' => 0, 'advance_paid' => 0], $reservasStatus);
        
        $reservasByHour = Reserva::select(DB::raw('HOUR(start_time) as hour'), DB::raw('count(*) as count'))->groupBy('hour')->orderBy('hour')->pluck('count', 'hour')->toArray();
        $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
        $hourlyData = array_replace(array_fill(0, 24, 0), $reservasByHour);

        $allReservas = Reserva::with(['cancha', 'user' => function($q) { 
                $q->withTrashed(); 
            }])
            ->latest()
            ->paginate(15); 

        return view('admin.reports.reservas', compact('reservasStatus', 'hourlyLabels', 'hourlyData', 'allReservas'));
    }
    
    public function reportsUsuarios() {
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $userGrowth = User::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_year'), DB::raw('count(*) as count'))->where('created_at', '>=', $sixMonthsAgo)->groupBy('month_year')->orderBy('month_year')->get();
        $growthLabels = $userGrowth->pluck('month_year')->map(fn($date) => Carbon::parse($date.'-01')->format('M Y'));
        $growthData = $userGrowth->pluck('count');
        $rawRoles = User::select('role', DB::raw('count(*) as count'))->groupBy('role')->pluck('count', 'role')->toArray();
        $usersByRole = ['users' => $rawRoles['user'] ?? 0, 'owners' => $rawRoles['owner'] ?? 0, 'admins' => $rawRoles['admin'] ?? 0];
        return view('admin.reports.usuarios', compact('growthLabels', 'growthData', 'usersByRole'));
    }

    public function reportsCanchas() {
        $canchasByDistrict = DB::table('canchas')->join('districts', 'canchas.district_id', '=', 'districts.id')->select('districts.name', DB::raw('count(canchas.id) as count'))->groupBy('districts.name')->pluck('count', 'districts.name')->toArray();
        
        $topFavoritas = Cancha::withCount('favorites')->orderBy('favorites_count', 'desc')->take(5)->get();
        $favLabels = $topFavoritas->pluck('name')->toArray();
        $favData   = $topFavoritas->pluck('favorites_count')->toArray();

        $detailedTopCanchas = Cancha::with('district', 'user')->withCount('reservas')
            ->withSum(['reservas' => function($q) { 
                $q->where('status', 'fully_paid'); 
            }], 'total_price')
            ->orderByDesc('reservas_count')->get();

        return view('admin.reports.canchas', compact('canchasByDistrict', 'detailedTopCanchas', 'favLabels', 'favData'));
    }
}