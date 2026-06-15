<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Category;
use App\Models\Game;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Marketplace';
    protected static ?string $navigationLabel = 'Items';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Item')
                ->schema([
                    Forms\Components\Select::make('game_id')
                        ->label('Game')
                        ->options(Game::query()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->options(Category::query()->where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Item')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(Item::class, 'slug', ignoreRecord: true),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Harga, Promo & Stok')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Harga Normal')
                        ->numeric()
                        ->prefix('Rp')
                        ->minValue(0)
                        ->required(),

                    Forms\Components\TextInput::make('stock')
                        ->label('Stok')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    Forms\Components\TextInput::make('rarity')
                        ->label('Rarity')
                        ->placeholder('Common / Rare / Epic / Legendary')
                        ->maxLength(255),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    Forms\Components\Toggle::make('is_featured')
                        ->label('Featured')
                        ->default(false),

                    Forms\Components\Toggle::make('is_promo')
                        ->label('Promo / Flash Sale')
                        ->live()
                        ->default(false),

                    Forms\Components\TextInput::make('promo_price')
                        ->label('Harga Promo')
                        ->numeric()
                        ->prefix('Rp')
                        ->minValue(0)
                        ->visible(fn (Forms\Get $get) => (bool) $get('is_promo'))
                        ->required(fn (Forms\Get $get) => (bool) $get('is_promo')),

                    Forms\Components\DateTimePicker::make('promo_ends_at')
                        ->label('Promo Berakhir')
                        ->seconds(false)
                        ->visible(fn (Forms\Get $get) => (bool) $get('is_promo')),
                ])
                ->columns(2),

            Forms\Components\Section::make('Access Link Delivery')
                ->description('Aktifkan jika item membutuhkan link akses setelah pembayaran berhasil.')
                ->schema([
                    Forms\Components\Toggle::make('requires_access_link')
                        ->label('Butuh Link Akses?')
                        ->live()
                        ->default(false),

                    Forms\Components\TextInput::make('access_link')
                        ->label('Link Akses')
                        ->url()
                        ->placeholder('https://wa.me/... atau link game/private server')
                        ->visible(fn (Forms\Get $get) => (bool) $get('requires_access_link'))
                        ->required(fn (Forms\Get $get) => (bool) $get('requires_access_link')),

                    Forms\Components\Textarea::make('access_instruction')
                        ->label('Instruksi Akses')
                        ->rows(4)
                        ->placeholder('Contoh: Klik link ini setelah pembayaran berhasil dan pastikan akun game kamu sedang online.')
                        ->visible(fn (Forms\Get $get) => (bool) $get('requires_access_link')),
                ])
                ->columns(1),

            Forms\Components\Section::make('Gambar')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Item')
                        ->image()
                        ->directory('items')
                        ->disk('public')
                        ->visibility('public')
                        ->imageEditor(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('game.name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Normal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('promo_price')
                    ->label('Harga Promo')
                    ->money('IDR')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rarity')
                    ->label('Rarity')
                    ->badge()
                    ->placeholder('-'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_promo')
                    ->label('Promo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('requires_access_link')
                    ->label('Access Link')
                    ->boolean(),

                Tables\Columns\TextColumn::make('promo_ends_at')
                    ->label('Promo Berakhir')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('game_id')
                    ->label('Game')
                    ->options(Game::query()->pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::query()->pluck('name', 'id')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),

                Tables\Filters\TernaryFilter::make('is_promo')
                    ->label('Promo'),

                Tables\Filters\TernaryFilter::make('requires_access_link')
                    ->label('Butuh Access Link'),
            ])
            ->actions([
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}