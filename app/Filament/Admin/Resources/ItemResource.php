<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
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
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Marketplace';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Item')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('game_id')
                        ->relationship('game', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Forms\Components\TextInput::make('stock')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    Forms\Components\Select::make('rarity')
                        ->options([
                            'common' => 'Common',
                            'rare' => 'Rare',
                            'epic' => 'Epic',
                            'legendary' => 'Legendary',
                            'mythic' => 'Mythic',
                        ])
                        ->default('common')
                        ->required(),

                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('items')
                        ->disk('public'),

                    Forms\Components\Toggle::make('is_featured')->default(false),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ]),

            Forms\Components\Section::make('Deskripsi')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('image')->disk('public'),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('game.name')->label('Game')->sortable(),
            Tables\Columns\TextColumn::make('category.name')->label('Kategori')->sortable(),
            Tables\Columns\TextColumn::make('price')->money('IDR')->sortable(),
            Tables\Columns\TextColumn::make('stock')->sortable(),

            Tables\Columns\BadgeColumn::make('rarity')
                ->colors([
                    'gray' => 'common',
                    'info' => 'rare',
                    'warning' => 'epic',
                    'success' => 'legendary',
                    'danger' => 'mythic',
                ]),

            Tables\Columns\IconColumn::make('is_featured')->boolean(),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('game_id')
                ->relationship('game', 'name'),

            Tables\Filters\SelectFilter::make('category_id')
                ->relationship('category', 'name'),

            Tables\Filters\SelectFilter::make('rarity')
                ->options([
                    'common' => 'Common',
                    'rare' => 'Rare',
                    'epic' => 'Epic',
                    'legendary' => 'Legendary',
                    'mythic' => 'Mythic',
                ]),
        ])
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}