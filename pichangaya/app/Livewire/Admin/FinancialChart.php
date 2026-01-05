<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Reserva;
use Carbon\Carbon;

class FinancialChart extends Component
{
    public $range = '15d';

    public function setRange($newRange)
    {
        $this->range = $newRange;
        // Enviamos el evento con los nuevos datos
        $this->dispatch('update-chart', data: $this->getChartData());
    }

    private function getChartData()
    {
        $days = match($this->range) {
            'day'   => 1,
            'week'  => 7,
            '15d'   => 15,
            'month' => 30,
            'year'  => 365,
            default => 15
        };

        $fechas = [];
        $dataFullyPaid = [];
        $dataAdvance = [];
        $dataPending = [];
        $dataCancelled = [];

        if ($days === 1) {
            // Rango de 24 horas (Hoy)
            for ($i = 0; $i <= 23; $i++) {
                $start = Carbon::now()->startOfDay()->addHours($i);
                $end   = $start->copy()->endOfHour();
                
                $fechas[] = $start->format('H:00');
                $dataFullyPaid[] = (float) Reserva::whereBetween('created_at', [$start, $end])->where('status', 'fully_paid')->sum('total_price');
                $dataAdvance[]   = (float) Reserva::whereBetween('created_at', [$start, $end])->where('status', 'advance_paid')->sum('total_price');
                $dataPending[]   = (float) Reserva::whereBetween('created_at', [$start, $end])->where('status', 'pending')->sum('total_price');
                $dataCancelled[] = (float) Reserva::whereBetween('created_at', [$start, $end])->where('status', 'cancelled')->sum('total_price');
            }
        } else {
            // Rango por días
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $fechas[] = $date->format('d/m');
                $dateString = $date->toDateString();

                $dataFullyPaid[] = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'fully_paid')->sum('total_price');
                $dataAdvance[]   = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'advance_paid')->sum('total_price');
                $dataPending[]   = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'pending')->sum('total_price');
                $dataCancelled[] = (float) Reserva::whereDate('created_at', $dateString)->where('status', 'cancelled')->sum('total_price');
            }
        }

        return [
            'labels' => $fechas,
            'datasets' => [
                'paid' => $dataFullyPaid,
                'advance' => $dataAdvance,
                'pending' => $dataPending,
                'cancelled' => $dataCancelled,
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.financial-chart', [
            'chartData' => $this->getChartData()
        ]);
    }
}