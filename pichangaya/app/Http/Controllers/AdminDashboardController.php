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
    // 🟢 DASHBOARD PRINCIPAL
    public function index()
    {
        // 1. KPIs Generales
        $totalUsers = User::count();
        $totalCanchas = Cancha::count();
        $reservasTotales = Reserva::count();

        // 2. ESTADOS FINANCIEROS (Sumas separadas)
        $ingresosTotales = Reserva::where('status', 'confirmed')->sum('total_price'); // Verde
        $adelantosTotal  = Reserva::where('status', 'advance_paid')->sum('total_price'); // Azul (Nuevo)
        $pendientesMoney = Reserva::where('status', 'pending')->sum('total_price'); // Amarillo
        $canceladosMoney = Reserva::where('status', 'cancelled')->sum('total_price'); // Rojo

        // Conteos
        $adelantosCount  = Reserva::where('status', 'advance_paid')->count();
        $pendientesCount = Reserva::where('status', 'pending')->count();
        $canceladosCount = Reserva::where('status', 'cancelled')->count();

        // 3. Gráfico Principal (Ingresos Reales = Confirmados + Adelantados)
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dailyData = Reserva::select(
                DB::raw('DATE(start_time) as date'),
                DB::raw('SUM(total_price) as income')
            )
            ->whereIn('status', ['confirmed', 'advance_paid']) // Sumamos lo que ya es dinero real
            ->whereBetween('start_time', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartIncomeData = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayData = $dailyData->firstWhere('date', $dateStr);
            $chartLabels[] = $currentDate->format('d/m');
            $chartIncomeData[] = $dayData ? $dayData->income : 0;
            $currentDate->addDay();
        }

        // 4. Datos Usuarios (Pastel)
        $rawRoles = User::select('role', DB::raw('count(*) as count'))->groupBy('role')->pluck('count', 'role')->toArray();
        $usersByRole = [
            'users'  => $rawRoles['user'] ?? 0,
            'owners' => $rawRoles['owner'] ?? 0,
            'admins' => $rawRoles['admin'] ?? 0,
        ];

        // 5. Tablas
        $recentReservas = Reserva::with(['cancha', 'user'])->latest()->take(5)->get();
        $topCanchas = Cancha::withCount('reservas')->orderByDesc('reservas_count')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalCanchas', 'reservasTotales', 
            'ingresosTotales', 
            'adelantosTotal', 'adelantosCount', 
            'pendientesCount', 'pendientesMoney',
            'canceladosCount', 'canceladosMoney',
            'usersByRole', 'recentReservas', 'topCanchas',
            'chartLabels', 'chartIncomeData'
        ));
    }

    // --- REPORTES DETALLADOS ---

    public function reportsIngresos()
    {
        // Solo 'confirmed'
        $monthlyIncome = Reserva::select(DB::raw('MONTH(start_time) as month'), DB::raw('SUM(total_price) as income'))
            ->where('status', 'confirmed')
            ->whereYear('start_time', Carbon::now()->year)
            ->groupBy('month')->pluck('income', 'month')->toArray();
            
        $monthlyLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $monthlyData = array_replace(array_fill(1, 12, 0), $monthlyIncome);

        $incomeByCancha = Cancha::withSum(['reservas' => function($q) { $q->where('status', 'confirmed'); }], 'total_price')
            ->orderByDesc('reservas_sum_total_price')->get();

        return view('admin.reports.ingresos', compact('monthlyLabels', 'monthlyData', 'incomeByCancha'));
    }

    // 🟢 NUEVO: REPORTE ADELANTADOS
    public function reportsAdelantados()
    {
        $adelantados = Reserva::with(['cancha.user', 'user', 'cancha'])
            ->where('status', 'advance_paid')
            ->orderBy('start_time', 'asc')
            ->get();

        $totalAdelanto = $adelantados->sum('total_price');
        $countAdelanto = $adelantados->count();

        // Gráfico por Cancha
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
        $pendientes = Reserva::with(['cancha.user', 'user', 'cancha'])->where('status', 'pending')->orderBy('start_time', 'asc')->get();
        $totalPendiente = $pendientes->sum('total_price');
        $countPendiente = $pendientes->count();
        $pendingByCancha = Reserva::where('status', 'pending')->join('canchas', 'reservas.cancha_id', '=', 'canchas.id')->select('canchas.name', DB::raw('SUM(reservas.total_price) as total'))->groupBy('canchas.name')->pluck('total', 'canchas.name')->toArray();

        return view('admin.reports.pendientes', compact('pendientes', 'totalPendiente', 'countPendiente', 'pendingByCancha'));
    }

    public function reportsCancelados()
    {
        $cancelados = Reserva::with(['cancha.user', 'user', 'cancha'])->where('status', 'cancelled')->orderBy('updated_at', 'desc')->get();
        $totalPerdido = $cancelados->sum('total_price');
        $countCancelados = $cancelados->count();
        $lostByDistrict = Reserva::where('status', 'cancelled')->join('canchas', 'reservas.cancha_id', '=', 'canchas.id')->join('districts', 'canchas.district_id', '=', 'districts.id')->select('districts.name', DB::raw('SUM(reservas.total_price) as total'))->groupBy('districts.name')->pluck('total', 'districts.name')->toArray();

        return view('admin.reports.cancelados', compact('cancelados', 'totalPerdido', 'countCancelados', 'lostByDistrict'));
    }

    // Mantener los otros métodos (Reservas, Usuarios, Canchas) igual que antes...
    public function reportsReservas() {
        $reservasStatus = Reserva::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status')->toArray();
        $reservasStatus = array_merge(['confirmed' => 0, 'pending' => 0, 'cancelled' => 0, 'advance_paid' => 0], $reservasStatus);
        $reservasByHour = Reserva::select(DB::raw('HOUR(start_time) as hour'), DB::raw('count(*) as count'))->groupBy('hour')->orderBy('hour')->pluck('count', 'hour')->toArray();
        $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
        $hourlyData = array_replace(array_fill(0, 24, 0), $reservasByHour);
        return view('admin.reports.reservas', compact('reservasStatus', 'hourlyLabels', 'hourlyData'));
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
        $detailedTopCanchas = Cancha::with('district', 'user')->withCount('reservas')->withSum(['reservas' => function($q) { $q->where('status', 'confirmed'); }], 'total_price')->orderByDesc('reservas_count')->get();
        return view('admin.reports.canchas', compact('canchasByDistrict', 'detailedTopCanchas'));
    }
}