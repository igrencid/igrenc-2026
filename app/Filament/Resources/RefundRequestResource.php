<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefundRequestResource\Pages;
use App\Models\RefundRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RefundRequestResource extends Resource
{
    protected static ?string $model = RefundRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Refund Requests';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('order.invoice_number')
                ->label('Invoice')
                ->disabled(),

            Forms\Components\Select::make('refund_method')
                ->label('Metode Refund')
                ->options([
                    'bank' => 'Bank Transfer',
                    'ewallet' => 'E-Wallet',
                ])
                ->required(),

            Forms\Components\TextInput::make('account_name')
                ->label('Nama Akun')
                ->required(),

            Forms\Components\TextInput::make('account_number')
                ->label('Nomor Rekening / E-Wallet')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'processed' => 'Processed',
                    'rejected' => 'Rejected',
                ])
                ->default('pending')
                ->required(),

            Forms\Components\Textarea::make('reason')
                ->label('Alasan')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('admin_notes')
                ->label('Catatan Admin')
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order.invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.customer_name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('refund_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bank' => 'Bank Transfer',
                        'ewallet' => 'E-Wallet',
                        default => ucfirst((string) $state),
                    }),

                Tables\Columns\TextColumn::make('account_name')
                    ->label('Nama Akun')
                    ->searchable(),

                Tables\Columns\TextColumn::make('account_number')
                    ->label('Nomor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'processed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Diproses')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('process')
                    ->label('Process')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn (RefundRequest $record) => $record->status === 'pending')
                    ->action(function (RefundRequest $record) {
                        $record->update([
                            'status' => 'processed',
                            'processed_at' => now(),
                            'processed_by' => auth()->id(),
                        ]);
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (RefundRequest $record) => $record->status === 'pending')
                    ->action(function (RefundRequest $record) {
                        $record->update([
                            'status' => 'rejected',
                            'processed_at' => now(),
                            'processed_by' => auth()->id(),
                        ]);
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefundRequests::route('/'),
            'create' => Pages\CreateRefundRequest::route('/create'),
            'edit' => Pages\EditRefundRequest::route('/{record}/edit'),
        ];
    }
}