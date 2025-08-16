<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\UserResource\RelationManagers\ListingsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nickname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\Select::make('role')
                    ->required()
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ]),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'standard' => 'Standard',
                        'premium' => 'Premium',
                        'partner' => 'Partner',
                    ]),
                Forms\Components\DateTimePicker::make('subscribed_at'),
                Forms\Components\TextInput::make('max_listings')
                    ->numeric()
                    ->required()
                    ->step(1),
                Forms\Components\TextInput::make('boosts')
                    ->numeric()
                    ->required()
                    ->step(1),
                Forms\Components\TextInput::make('password')
                    ->maxLength(255),
                Forms\Components\Checkbox::make('is_active')
                    ->default(true),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('1280')
                    ->imageResizeTargetHeight('720'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('nickname')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('role')->searchable(),
                Tables\Columns\TextColumn::make('type')->searchable(),
                Tables\Columns\TextColumn::make('max_listings'),
                Tables\Columns\TextColumn::make('password'),
                Tables\Columns\CheckboxColumn::make('is_active'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            'events' => ListingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
