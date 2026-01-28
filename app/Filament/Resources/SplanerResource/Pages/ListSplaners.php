<?php

namespace App\Filament\Resources\SplanerResource\Pages;

use App\Filament\Resources\SplanerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSplaners extends ListRecords
{
    protected static string $resource = SplanerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\SplanerResource\Widgets\SplanerCalendarWidget::class,
        ];
    }
}
