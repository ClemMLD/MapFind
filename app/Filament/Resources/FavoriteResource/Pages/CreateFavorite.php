<?php

namespace App\Filament\Resources\FavoriteResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\FavoriteResource;

class CreateFavorite extends CreateRecord
{
    protected static string $resource = FavoriteResource::class;
}
