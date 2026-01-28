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

        Notification::make()
            ->title('Disposisi Baru')
            ->body("**{$sender->name}** mengirim disposisi untuk surat: **{$disposisi->suratMasuk->nomor_surat}**")
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
