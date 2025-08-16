<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Message;
use Filament\Resources\Resource;
use App\Filament\Resources\MessageResource\Pages;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sender_id')
                    ->required()
                    ->label('Sender')
                    ->options(function () {
                        return User::all()->pluck('nickname', 'id');
                    }),
                Forms\Components\Select::make('receiver_id')
                    ->required()
                    ->label('Receiver')
                    ->options(function () {
                        return User::all()->pluck('nickname', 'id');
                    }),
                Forms\Components\Textarea::make('content')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender.nickname')->searchable(),
                Tables\Columns\TextColumn::make('receiver.nickname')->searchable(),
                Tables\Columns\TextColumn::make('content')->searchable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessage::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
