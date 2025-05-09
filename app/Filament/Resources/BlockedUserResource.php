<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\BlockedUser;
use Filament\Resources\Resource;
use App\Filament\Resources\BlockedUserResource\Pages;

class BlockedUserResource extends Resource
{
    protected static ?string $model = BlockedUser::class;
    protected static ?string $navigationIcon = 'heroicon-o-signal-slash';

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
                Forms\Components\Select::make('blocked_user_id')
                    ->required()
                    ->label('Blocked User')
                    ->options(function () {
                        return User::all()->pluck('nickname', 'id');
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
                Tables\Columns\TextColumn::make('blockedUser.nickname')
                    ->label('Blocked User')
                    ->searchable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlockedUser::route('/'),
            'create' => Pages\CreateBlockedUser::route('/create'),
            'edit' => Pages\EditBlockedUser::route('/{record}/edit'),
        ];
    }
}
