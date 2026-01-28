<?php

namespace App\Filament\Resources\ReviewSuratResource\Pages;

use App\Filament\Resources\ReviewSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReviewSurat extends EditRecord
{
    protected static string $resource = ReviewSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
