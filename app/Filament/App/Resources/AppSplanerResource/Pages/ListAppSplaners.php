<?php

namespace App\Filament\App\Resources\AppSplanerResource\Pages;

use App\Filament\App\Resources\AppSplanerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppSplaners extends ListRecords
{
    protected static string $resource = AppSplanerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AppSplanerResource\Widgets\AppSplanerCalendarWidget::class,
        ];
    }
}
