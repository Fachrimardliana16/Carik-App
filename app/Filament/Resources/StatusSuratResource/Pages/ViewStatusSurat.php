<?php

namespace App\Filament\Resources\StatusSuratResource\Pages;

use App\Filament\Resources\StatusSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStatusSurat extends ViewRecord
{
    protected static string $resource = StatusSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
