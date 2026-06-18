<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Order;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->label('Invoice Order')
                ->options(Order::query()->pluck('invoice_number', 'id'))
                ->searchable()
                ->preload()
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('payment_method')
                ->label('Metode Pembayaran')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('transaction_id')
                ->label('Transaction ID')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('midtrans_order_id')
                ->label('Midtrans Order ID')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('amount')
                ->label('Jumlah Bayar')
                ->prefix('Rp')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('status')
                ->label('Status Pembayaran')
                ->disabled()
                ->dehydrated(false)
                ->formatStateUsing(fn ($state) => match ($state) {
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'expired' => 'Expired',
                    default => ucfirst(str_replace('_', ' ', (string) $state)),
                }),

            Forms\Components\DateTimePicker::make('verified_at')
                ->label('Tanggal Verifikasi')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->disabled()
                ->dehydrated(false)
                ->columnSpanFull(),

            Forms\Components\Textarea::make('snap_url')
                ->label('Snap URL')
                ->disabled()
                ->dehydrated(false)
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

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'midtrans' => 'Midtrans',
                        'qris' => 'QRIS',
                        'gopay' => 'GoPay',
                        'shopeepay' => 'ShopeePay',
                        'bank_transfer' => 'Bank Transfer',
                        'credit_card' => 'Credit Card',
                        default => ucfirst(str_replace('_', ' ', (string) $state)),
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'expired' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                        default => ucfirst(str_replace('_', ' ', (string) $state)),
                    }),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('midtrans_order_id')
                    ->label('Midtrans Order ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Dibayar')
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
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}