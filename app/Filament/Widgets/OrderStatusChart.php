<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Order';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $orders = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Order',
                    'data' => array_values($orders),
                    'backgroundColor' => [
                        '#f59e0b',
                        '#22c55e',
                        '#3b82f6',
                        '#ef4444',
                        '#a855f7',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_map('ucfirst', array_keys($orders)),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '65%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}