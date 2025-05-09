<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Listing;
use Filament\Forms\Form;
use App\Models\Favorite;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\FavoriteResource\Pages;

class FavoriteResource extends Resource
{
    protected static ?string $model = Favorite::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->label('User')
                    ->options(function () {
                        return User::all()->pluck('nickname', 'id');
                    }),
                Forms\Components\Select::make('listing_id')
                    ->required()
                    ->label('Listing')
                    ->options(function () {
                        return Listing::all()->pluck('title', 'id');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.nickname')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('listing.title')
                    ->label('Listing')
                    ->searchable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFavorite::route('/'),
            'create' => Pages\CreateFavorite::route('/create'),
            'edit' => Pages\EditFavorite::route('/{record}/edit'),
        ];
    }
}
