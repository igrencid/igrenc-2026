<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Order Per Bulan';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $orders = Order::query()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $data[] = $orders[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Order',
                    'data' => $data,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}