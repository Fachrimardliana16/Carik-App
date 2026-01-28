<?php

namespace App\Filament\Resources\TembusanResource\Pages;

use App\Filament\Resources\TembusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTembusan extends EditRecord
{
    protected static string $resource = TembusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
