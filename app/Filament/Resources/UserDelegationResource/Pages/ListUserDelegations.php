<?php

namespace App\Filament\Resources\UserDelegationResource\Pages;

use App\Filament\Resources\UserDelegationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserDelegations extends ListRecords
{
    protected static string $resource = UserDelegationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
