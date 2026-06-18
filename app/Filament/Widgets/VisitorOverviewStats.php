<?php

namespace App\Filament\Widgets;

use App\Models\VisitorLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorOverviewStats extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today = today();

        $total = VisitorLog::count();
        $todayCount = VisitorLog::whereDate('visited_at', $today)->count();
        $uniqueToday = VisitorLog::whereDate('visited_at', $today)->whereNotNull('session_id')->distinct('session_id')->count('session_id');

        $popular = VisitorLog::selectRaw('url, COUNT(*) as total')
            ->groupBy('url')
            ->orderByDesc('total')
            ->limit(1)
            ->pluck('url')
            ->first() ?? '-';

        return [
            Stat::make('Total Kunjungan', $total)
                ->description('Semua kunjungan')
                ->icon('heroicon-m-eye')
                ->color('primary'),

            Stat::make('Kunjungan Hari Ini', $todayCount)
                ->description('Kunjungan hari ini')
                ->icon('heroicon-m-calendar-days')
                ->color('success'),

            Stat::make('Visitor Unik Hari Ini', $uniqueToday)
                ->description('Sesi unik hari ini')
                ->icon('heroicon-m-users')
                ->color('info'),

            Stat::make('Halaman Terpopuler', $popular)
                ->description('Halaman terpopuler')
                ->icon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
