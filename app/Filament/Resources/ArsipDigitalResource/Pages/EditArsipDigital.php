<?php

namespace App\Filament\Resources\ArsipDigitalResource\Pages;

use App\Filament\Resources\ArsipDigitalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArsipDigital extends EditRecord
{
    protected static string $resource = ArsipDigitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
