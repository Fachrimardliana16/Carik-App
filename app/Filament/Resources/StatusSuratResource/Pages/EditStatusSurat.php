<?php

namespace App\Filament\Resources\StatusSuratResource\Pages;

use App\Filament\Resources\StatusSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatusSurat extends EditRecord
{
    protected static string $resource = StatusSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
