<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ItemsByGameChart;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\OrderStatusChart;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            RevenueChart::class,
            OrdersChart::class,
            OrderStatusChart::class,
            ItemsByGameChart::class,
        ];
    }
}