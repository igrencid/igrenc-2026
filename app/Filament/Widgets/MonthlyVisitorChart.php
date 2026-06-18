<?php

namespace App\Filament\Widgets;

use App\Models\VisitorLog;
use Filament\Widgets\ChartWidget;

class MonthlyVisitorChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Kunjungan Bulanan';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $visitors = VisitorLog::selectRaw('MONTH(visited_at) as month, COUNT(*) as total')
            ->whereYear('visited_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $data[] = $visitors[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kunjungan',
                    'data' => $data,
                    'backgroundColor' => '#0ea5e9',
                    'borderColor' => '#7dd3fc',
                    'borderWidth' => 1,
                    'borderRadius' => 12,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
