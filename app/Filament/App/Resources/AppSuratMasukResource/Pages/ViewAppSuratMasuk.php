<?php

namespace App\Filament\App\Resources\AppSuratMasukResource\Pages;

use App\Filament\App\Resources\AppSuratMasukResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAppSuratMasuk extends ViewRecord
{
    protected static string $resource = AppSuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('terima')
                ->label('Terima')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => 
                    ($record->statusSurat?->nama === 'Draft' && auth()->user()->hasAnyRole(['admin', 'super_admin'])) ||
                    ($record->tujuan_user_id === auth()->id() && $record->status === 'Pending' && auth()->user()->hasAnyRole(['direktur', 'kepala_bagian', 'kepala_sub_bagian', 'staff'])) ||
                    ($record->disposisis()->where('kepada_user_id', auth()->id())->where('status', 'Pending')->exists() && auth()->user()->hasAnyRole(['direktur', 'kepala_bagian', 'kepala_sub_bagian', 'staff']))
                )
                ->action(function ($record) {
                    $user = auth()->user();
                    $isStaff = $user->hasRole('staff');
                    $statusToSet = $isStaff ? 'Selesai' : 'Diterima';

                    if ($record->statusSurat?->nama === 'Draft') {
                        $statusDefault = \App\Models\StatusSurat::where('nama', '!=', 'Draft')->where('is_default', true)->first();
                        if ($statusDefault) {
                            $record->update(['status_surat_id' => $statusDefault->id]);
                        }
                    }
                    $disposisiRef = $record->disposisis()->where('kepada_user_id', $user->id)->where('status', 'Pending');
                    $updateData = ['status' => $statusToSet, 'dibaca_pada' => now()];
                    if ($isStaff) {
                        $updateData['selesai_pada'] = now();
                    }
                    $disposisiRef->update($updateData);

                    if ($record->tujuan_user_id === $user->id) {
                        $record->update(['status' => $statusToSet]);
                    }
                    $msg = $isStaff ? 'Surat Berhasil Diterima dan Diselesaikan' : 'Surat Berhasil Diterima';
                    \Filament\Notifications\Notification::make()->title($msg)->success()->send();
                }),
            \Filament\Actions\Action::make('buat_disposisi')
                ->label('Buat Disposisi')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->visible(function ($record) {
                     $user = auth()->user();
                     if (!$user->hasAnyRole(['super_admin', 'admin', 'sekretaris', 'direktur', 'kepala_bagian', 'kepala_sub_bagian'])) return false;
                     
                     // Admin/Sekretaris can always dispose (unless restricted, but usually they distribute)
                     if ($user->hasAnyRole(['super_admin', 'admin', 'sekretaris']) && $record->disposisis()->count() == 0) return true;

                     // If acts as Main Recipient
                     if ($record->tujuan_user_id === $user->id) {
                         return $record->status === 'Diterima';
                     }
                     
                     // If acts as Disposition Recipient
                     $usersDisposition = $record->disposisis()->where('kepada_user_id', $user->id)->latest()->first();
                     if ($usersDisposition) {
                         // Only visible if status is Diterima
                         return $usersDisposition->status === 'Diterima';
                     }

                     if ($user->hasAnyRole(['super_admin', 'admin'])) return true;

                     return false;
                })
                ->form([
                    \Filament\Forms\Components\Select::make('kepada_user_ids')
                        ->label('Tujuan Disposisi')
                        ->options(fn () => \App\Models\User::pluck('name', 'id'))
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('instruksi')->label('Instruksi')->required(),
                    \Filament\Forms\Components\Select::make('prioritas')
                        ->options(['Biasa' => 'Biasa', 'Penting' => 'Penting', 'Segera' => 'Segera', 'Sangat Segera' => 'Sangat Segera'])
                        ->default('Biasa')->required(),
                    \Filament\Forms\Components\DatePicker::make('batas_waktu')->label('Batas Waktu'),
                ])
                ->action(function ($record, array $data) {
                    foreach ($data['kepada_user_ids'] as $kepadaUserId) {
                        $record->disposisis()->create([
                            'dari_user_id' => auth()->id(),
                            'kepada_user_id' => $kepadaUserId,
                            'instruksi' => $data['instruksi'],
                            'prioritas' => $data['prioritas'],
                            'batas_waktu' => $data['batas_waktu'],
                            'status' => 'Pending',
                        ]);
                    }
                    $record->disposisis()->where('kepada_user_id', auth()->id())->where('status', 'Diterima')->update(['status' => 'Selesai', 'selesai_pada' => now()]);
                    if (auth()->user()->hasRole('direktur')) {
                        $statusSigned = \App\Models\StatusSurat::where('nama', 'Signed')->first();
                        if ($statusSigned) $record->update(['status_surat_id' => $statusSigned->id, 'status' => 'Selesai']);
                    }
                    \Filament\Notifications\Notification::make()->title('Disposisi Berhasil Dibuat')->success()->send();
                }),
        ];
    }
}
