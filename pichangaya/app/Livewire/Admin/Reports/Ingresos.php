<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Ingresos extends Component
{
    public $range = 'year'; // Por defecto: Año actual

    public function setRange($range)
    {
        $this->range = $range;
        // Al cambiar el rango, avisamos al frontend para redibujar el gráfico
        $this->dispatch('update-ingresos-chart', data: $this->getChartData());
    }

    public function getChartData()
    {
        $now = Carbon::now();
        $labels = [];
        $data = [];

        // Lógica según el rango seleccionado
        switch ($this->range) {
            case 'day': // Hoy (por horas)
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                
                // Inicializar 24 horas
                $hours = range(0, 23);
                $labels = array_map(fn($h) => sprintf("%02d:00", $h), $hours);
                $emptyData = array_fill(0, 24, 0);

                // Consulta
                $reservas = Reserva::select(DB::raw('HOUR(start_time) as hour'), DB::raw('SUM(total_price) as total'))
                    ->where('status', 'fully_paid')
                    ->whereBetween('start_time', [$start, $end])
                    ->groupBy('hour')
                    ->pluck('total', 'hour')->toArray();

                $data = array_replace($emptyData, $reservas);
                break;

            case 'week': // Últimos 7 días
            case 'month': // Últimos 30 días
                $days = ($this->range === 'week') ? 7 : 30;
                
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $labels[] = $date->format('d/m'); // Ej: 05/01
                    $dateString = $date->toDateString();

                    $total = Reserva::whereDate('start_time', $dateString)
                        ->where('status', 'fully_paid')
                        ->sum('total_price');
                    
                    $data[] = (float) $total;
                }
                break;

            case 'year': // Año actual (por meses)
            default:
                $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                $emptyData = array_fill(1, 12, 0); // Índices 1-12

                $reservas = Reserva::select(DB::raw('MONTH(start_time) as month'), DB::raw('SUM(total_price) as total'))
                    ->where('status', 'fully_paid')
                    ->whereYear('start_time', $now->year)
                    ->groupBy('month')
                    ->pluck('total', 'month')->toArray();

                $dataWithIndices = array_replace($emptyData, $reservas);
                $data = array_values($dataWithIndices); // 🚨 IMPORTANTE: Eliminar índices para JS
                break;
        }

        // Devolver estructura limpia para Chart.js
        return [
            'labels' => $labels,
            'values' => array_values($data) // Aseguramos array numérico
        ];
    }

    public function render()
    {
        // Obtener la lista detallada filtrada según el rango seleccionado
        $query = Reserva::with(['user', 'cancha'])
            ->where('status', 'fully_paid')
            ->orderBy('start_time', 'desc');

        if ($this->range === 'day') {
            $query->whereDate('start_time', Carbon::today());
        } elseif ($this->range === 'week') {
            $query->where('start_time', '>=', Carbon::now()->subDays(7));
        } elseif ($this->range === 'month') {
            $query->where('start_time', '>=', Carbon::now()->subDays(30));
        } elseif ($this->range === 'year') {
            $query->whereYear('start_time', Carbon::now()->year);
        }

        $ingresosDetallados = $query->get();

        return view('livewire.admin.reports.ingresos', [
            'chartData' => $this->getChartData(),
            'ingresosDetallados' => $ingresosDetallados
        ]);
    }
}