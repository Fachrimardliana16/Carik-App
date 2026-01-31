<?php

namespace App\Filament\Resources\ReviewSuratResource\Pages;

use App\Filament\Resources\ReviewSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReviewSurats extends ListRecords
{
    protected static string $resource = ReviewSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ReviewSuratResource\Widgets\ReviewSuratStats::class,
        ];
    }
}
