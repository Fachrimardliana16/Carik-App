<?php

namespace App\Filament\Resources\KlasifikasiArsipResource\Pages;

use App\Filament\Resources\KlasifikasiArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKlasifikasiArsip extends ViewRecord
{
    protected static string $resource = KlasifikasiArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
