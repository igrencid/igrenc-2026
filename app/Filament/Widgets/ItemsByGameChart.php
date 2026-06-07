<?php

namespace App\Filament\Widgets;

use App\Models\Game;
use Filament\Widgets\ChartWidget;

class ItemsByGameChart extends ChartWidget
{
    protected static ?string $heading = 'Item Per Game';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $games = Game::withCount('items')
            ->having('items_count', '>', 0)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Item',
                    'data' => $games->pluck('items_count')->toArray(),
                    'backgroundColor' => [
                        '#8b5cf6',
                        '#06b6d4',
                        '#22c55e',
                        '#f59e0b',
                        '#ef4444',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $games->pluck('name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}