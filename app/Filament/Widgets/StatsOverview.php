<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Game;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Games', Game::count())
                ->description('Total game')
                ->color('info'),

            Stat::make('Categories', Category::count())
                ->description('Total kategori')
                ->color('warning'),

            Stat::make('Items Aktif', Item::where('is_active', true)->count())
                ->description('Item tersedia')
                ->color('success'),

            Stat::make('Orders', Order::count())
                ->description('Total transaksi')
                ->color('primary'),

            Stat::make('Payments', Payment::count())
                ->description('Data pembayaran')
                ->color('danger'),

            Stat::make(
                'Revenue',
                'Rp ' . number_format(Order::sum('total_price'), 0, ',', '.')
            )
                ->description('Total omzet')
                ->color('success'),
        ];
    }
}