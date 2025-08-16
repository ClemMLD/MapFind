<?php

namespace App\Filament\Resources\BlockedUserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BlockedUserResource;

class CreateBlockedUser extends CreateRecord
{
    protected static string $resource = BlockedUserResource::class;
}
