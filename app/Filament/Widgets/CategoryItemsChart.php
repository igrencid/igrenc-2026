<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class CategoryItemsChart extends ChartWidget
{
    protected static ?string $heading = 'Item Per Kategori';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = Category::query()
            ->withCount('items')
            ->having('items_count', '>', 0)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Item',
                    'data' => $categories->pluck('items_count')->toArray(),
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}