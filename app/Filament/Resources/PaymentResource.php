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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->label('Invoice Order')
                ->options(Order::query()->pluck('invoice_number', 'id'))
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('payment_method')
                ->label('Metode Pembayaran')
                ->options([
                    'manual' => 'Manual Transfer',
                    'bank_transfer' => 'Bank Transfer',
                    'qris' => 'QRIS',
                    'ewallet' => 'E-Wallet',
                ])
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('Jumlah Bayar')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status Pembayaran')
                ->options([
                    'pending' => 'Pending',
                    'waiting_verification' => 'Waiting Verification',
                    'paid' => 'Paid',
                    'rejected' => 'Rejected',
                ])
                ->default('pending')
                ->required(),

            Forms\Components\FileUpload::make('payment_proof')
                ->label('Bukti Pembayaran')
                ->image()
                ->disk('public')
                ->directory('payment-proofs')
                ->visibility('public')
                ->imageEditor(),

            Forms\Components\DateTimePicker::make('verified_at')
                ->label('Tanggal Verifikasi')
                ->disabled(),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
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
                        'manual' => 'Manual',
                        'bank_transfer' => 'Bank Transfer',
                        'qris' => 'QRIS',
                        'ewallet' => 'E-Wallet',
                        default => ucfirst((string) $state),
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'waiting_verification' => 'info',
                        'paid' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pending',
                        'waiting_verification' => 'Waiting',
                        'paid' => 'Paid',
                        'rejected' => 'Rejected',
                        default => ucfirst((string) $state),
                    }),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Diverifikasi')
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
                        'waiting_verification' => 'Waiting Verification',
                        'paid' => 'Paid',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Payment $record) => in_array($record->status, ['pending', 'waiting_verification']))
                    ->action(function (Payment $record) {
                        $record->update([
                            'status' => 'paid',
                            'verified_at' => now(),
                            'verified_by' => auth()->id(),
                        ]);

                        $record->order?->update([
                            'status' => 'completed',
                        ]);
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Payment $record) => in_array($record->status, ['pending', 'waiting_verification']))
                    ->action(function (Payment $record) {
                        $record->update([
                            'status' => 'rejected',
                            'verified_at' => now(),
                            'verified_by' => auth()->id(),
                        ]);

                        $record->order?->update([
                            'status' => 'cancelled',
                        ]);
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}