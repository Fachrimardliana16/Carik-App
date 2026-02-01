<?php

namespace App\Filament\App\Resources\AppArsipDigitalResource\Pages;

use App\Filament\App\Resources\AppArsipDigitalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppArsipDigitals extends ListRecords
{
    protected static string $resource = AppArsipDigitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
