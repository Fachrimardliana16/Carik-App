<?php

namespace App\Filament\Resources\NotulensiResource\Pages;

use App\Filament\Resources\NotulensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNotulensis extends ManageRecords
{
    protected static string $resource = NotulensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            NotulensiResource\Widgets\NotulensiStats::class,
        ];
    }
}
