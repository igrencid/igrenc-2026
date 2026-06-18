<?php

namespace App\Filament\Widgets;

use App\Models\VisitorLog;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentVisitsTable extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 2;

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(VisitorLog::query()->latest('visited_at')->limit(8))
            ->columns([
                Tables\Columns\TextColumn::make('visited_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('Halaman')
                    ->wrap(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address'),

                Tables\Columns\TextColumn::make('referer')
                    ->label('Referer')
                    ->wrap(),
            ])
            ->paginated(false);
    }
}
