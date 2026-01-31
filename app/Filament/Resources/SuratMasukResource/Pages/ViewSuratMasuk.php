<?php

namespace App\Filament\Resources\SuratMasukResource\Pages;

use App\Filament\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSuratMasuk extends ViewRecord
{
    protected static string $resource = SuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        
        $statusReview = \App\Models\StatusSurat::where('nama', 'Review')->first();
        if ($this->record->statusSurat?->nama === 'Sent' || $this->record->statusSurat?->nama === 'Draft') {
            $this->record->update([
                'status_surat_id' => $statusReview?->id,
            ]);
        }
    }
}
