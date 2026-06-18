<?php

namespace App\Filament\Widgets;

use App\Models\VisitorLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class WeeklyVisitorChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Kunjungan 7 Hari Terakhir';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $start = today()->subDays(6)->startOfDay();
        $end = today()->endOfDay();

        $visitors = VisitorLog::selectRaw('DATE(visited_at) as date, COUNT(*) as total')
            ->whereBetween('visited_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($day = 6; $day >= 0; $day--) {
            $date = Carbon::today()->subDays($day);
            $key = $date->toDateString();

            $labels[] = $date->format('d M');
            $data[] = $visitors[$key] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kunjungan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.16)',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
