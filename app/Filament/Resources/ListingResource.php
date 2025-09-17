<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Listing;
use Filament\Forms\Form;
use App\Models\Category;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ListingResource\Pages;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->required()
                    ->options(function () {
                        return Category::all()->pluck('name.' . App::getLocale(), 'id');
                    }),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('currency')
                    ->options(function () {
                        return collect(config('currency'))->keys()->mapWithKeys(function ($key) {
                            return [$key => $key];
                        });
                    })
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->required(),
                Forms\Components\TextInput::make('latitude')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('longitude')
                    ->numeric()
                    ->required(),
                Forms\Components\Toggle::make('boosted'),
                Forms\Components\DateTimePicker::make('boosted_at')
                    ->nullable(),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->searchable()
                    ->relationship(
                        name: 'user',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('name'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                    ->searchable(['name']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('category.name.' . App::getLocale())
                    ->label('Category'),
                Tables\Columns\TextColumn::make('price')->searchable(),
                Tables\Columns\TextColumn::make('address')->searchable(),
                Tables\Columns\TextColumn::make('latitude')->searchable(),
                Tables\Columns\TextColumn::make('longitude')->searchable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListing::route('/'),
            'create' => Pages\CreateListing::route('/create'),
            'edit' => Pages\EditListing::route('/{record}/edit'),
        ];
    }
}
