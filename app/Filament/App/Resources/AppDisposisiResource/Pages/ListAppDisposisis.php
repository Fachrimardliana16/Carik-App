<?php

namespace App\Filament\App\Resources\AppDisposisiResource\Pages;

use App\Filament\App\Resources\AppDisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppDisposisis extends ListRecords
{
    protected static string $resource = AppDisposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
