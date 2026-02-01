<?php

namespace App\Filament\App\Resources\AppSuratKeluarResource\Pages;

use App\Filament\App\Resources\AppSuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppSuratKeluars extends ListRecords
{
    protected static string $resource = AppSuratKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
