<?php

namespace App\Filament\Resources\BlockedUserResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BlockedUserResource;

class ListBlockedUser extends ListRecords
{
    protected static string $resource = BlockedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
