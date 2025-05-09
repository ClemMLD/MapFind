<?php

namespace App\Filament\Resources\BlockedUserResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BlockedUserResource;

class EditBlockedUser extends EditRecord
{
    protected static string $resource = BlockedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
