<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
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

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Pembayaran')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('order_id')
                        ->label('Invoice')
                        ->relationship('order', 'invoice_number')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->default('manual')
                        ->required(),

                    Forms\Components\TextInput::make('transaction_id')
                        ->label('ID Transaksi')
                        ->placeholder('Opsional'),

                    Forms\Components\TextInput::make('amount')
                        ->label('Total Pembayaran')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Status Pembayaran')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'expired' => 'Expired',
                        ])
                        ->default('pending')
                        ->required(),

                    Forms\Components\FileUpload::make('proof_image')
                        ->label('Bukti Transfer')
                        ->image()
                        ->disk('public')
                        ->directory('payment-proofs')
                        ->imagePreviewHeight('180')
                        ->downloadable()
                        ->openable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('proof_image')
                    ->label('Bukti')
                    ->disk('public')
                    ->height(60)
                    ->width(60),

                Tables\Columns\TextColumn::make('order.invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.customer_name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaksi')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'warning' => 'expired',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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