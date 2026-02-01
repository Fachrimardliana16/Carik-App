<?php

namespace App\Observers;

use App\Models\Disposisi;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class DisposisiObserver
{
    /**
     * Handle the Disposisi "created" event.
     */
    public function created(Disposisi $disposisi): void
    {
        $recipient = $disposisi->kepadaUser;
        $sender = $disposisi->dariUser;

        // Determine the surat (could be SuratMasuk or SuratKeluar)
        $surat = $disposisi->suratMasuk ?? $disposisi->suratKeluar;
        $nomorSurat = $surat?->nomor_surat ?? 'N/A';
        $suratType = $disposisi->surat_masuk_id ? 'Surat Masuk' : 'Surat Keluar';

        Notification::make()
            ->title('Disposisi Baru')
            ->body("**{$sender->name}** mengirim disposisi untuk {$suratType}: **{$nomorSurat}**")
            ->icon('heroicon-o-inbox-arrow-down')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.app.resources.app-disposisis.view', $disposisi)) // Link to App panel
                    ->button(),
            ])
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the Disposisi "updated" event.
     */
    public function updated(Disposisi $disposisi): void
    {
        if ($disposisi->isDirty('status')) {
            // Notify Sender that status changed (e.g. from Pending to Selesai)
            $recipient = $disposisi->dariUser;
            $actor = $disposisi->kepadaUser; // Assuming updated by recipient

            Notification::make()
                ->title('Update Disposisi')
                ->body("**{$actor->name}** mengubah status disposisi menjadi: **{$disposisi->status}**")
                ->success()
                ->sendToDatabase($recipient);
        }
    }
}
