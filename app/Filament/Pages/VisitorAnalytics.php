<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlyVisitorChart;
use App\Filament\Widgets\PopularPagesTable;
use App\Filament\Widgets\RecentVisitsTable;
use App\Filament\Widgets\TrafficSourceChart;
use App\Filament\Widgets\VisitorOverviewStats;
use App\Filament\Widgets\WeeklyVisitorChart;
use Filament\Pages\Page;

class VisitorAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Visitor Analytics';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.visitor-analytics';

    protected static ?string $title = 'Visitor Analytics';

    protected function getHeaderWidgets(): array
    {
        return [
            VisitorOverviewStats::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            WeeklyVisitorChart::class,
            TrafficSourceChart::class,
            MonthlyVisitorChart::class,
            PopularPagesTable::class,
            RecentVisitsTable::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }
}