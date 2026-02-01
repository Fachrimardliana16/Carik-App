<?php

namespace App\Filament\App\Resources\AppSuratMasukResource\Pages;

use App\Filament\App\Resources\AppSuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppSuratMasuks extends ListRecords
{
    protected static string $resource = AppSuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
