<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Customer')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')
                        ->required()
                        ->disabled(),

                    Forms\Components\TextInput::make('customer_name')
                        ->required(),

                    Forms\Components\TextInput::make('customer_email')
                        ->email()
                        ->required(),

                    Forms\Components\TextInput::make('customer_whatsapp'),

                    Forms\Components\TextInput::make('total_price')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'processing' => 'Processing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required(),
                ]),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->rows(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('invoice_number')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('customer_name')->searchable(),
            Tables\Columns\TextColumn::make('customer_email')->searchable(),
            Tables\Columns\TextColumn::make('total_price')->money('IDR')->sortable(),

            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'gray' => 'pending',
                    'success' => 'paid',
                    'warning' => 'processing',
                    'info' => 'completed',
                    'danger' => 'cancelled',
                ]),

            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}