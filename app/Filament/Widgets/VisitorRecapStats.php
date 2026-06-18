<?php

namespace App\Filament\Widgets;

use App\Models\VisitorLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorRecapStats extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Hari Ini', VisitorLog::whereDate('visited_at', today())->count())
                ->description('Kunjungan hari ini')
                ->icon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('Minggu Ini', VisitorLog::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count())
                ->description('Kunjungan minggu ini')
                ->icon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Bulan Ini', VisitorLog::whereBetween('visited_at', [now()->startOfMonth(), now()->endOfMonth()])->count())
                ->description('Kunjungan bulan ini')
                ->icon('heroicon-m-chart-bar')
                ->color('warning'),

            Stat::make('Tahun Ini', VisitorLog::whereBetween('visited_at', [now()->startOfYear(), now()->endOfYear()])->count())
                ->description('Kunjungan tahun ini')
                ->icon('heroicon-m-presentation-chart-line')
                ->color('primary'),

            Stat::make('Total Kunjungan', VisitorLog::count())
                ->description('Semua kunjungan')
                ->icon('heroicon-m-eye')
                ->color('gray'),
        ];
    }
}
