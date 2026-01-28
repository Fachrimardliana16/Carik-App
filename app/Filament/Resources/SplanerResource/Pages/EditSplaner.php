<?php

namespace App\Filament\Resources\SplanerResource\Pages;

use App\Filament\Resources\SplanerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSplaner extends EditRecord
{
    protected static string $resource = SplanerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
