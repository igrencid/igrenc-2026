<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ItemsByGameChart;
use App\Filament\Widgets\MonthlyVisitorChart;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\OrderStatusChart;
use App\Filament\Widgets\PopularPagesTable;
use App\Filament\Widgets\RecentVisitsTable;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\VisitorOverviewStats;
use App\Filament\Widgets\WeeklyVisitorChart;
use App\Filament\Widgets\TrafficSourceChart;
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
            VisitorOverviewStats::class,
            WeeklyVisitorChart::class,
            TrafficSourceChart::class,
            MonthlyVisitorChart::class,
            PopularPagesTable::class,
            RecentVisitsTable::class,
            RevenueChart::class,
            OrdersChart::class,
            OrderStatusChart::class,
            ItemsByGameChart::class,
        ];
    }
}