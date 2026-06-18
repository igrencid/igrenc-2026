<?php

namespace App\Filament\Widgets;

use App\Models\VisitorLog;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularPagesTable extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 2;

    public function table(Tables\Table $table): Tables\Table
    {
        $total = VisitorLog::count() ?: 1;

        return $table
            ->query(
                VisitorLog::selectRaw('url, COUNT(*) as visits')
                    ->groupBy('url')
                    ->orderByDesc('visits')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('url')
                    ->label('Halaman')
                    ->wrap()
                    ->toggleable(false),

                Tables\Columns\TextColumn::make('visits')
                    ->label('Kunjungan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('percentage')
                    ->label('Persentase')
                    ->formatStateUsing(function ($state, $record) use ($total) {
                        $visits = $record->visits ?? 0;
                        return round(($visits / $total) * 100, 1) . '%';
                    }),
            ])
            ->defaultSort('visits', 'desc')
            ->paginated(false);
    }
}
