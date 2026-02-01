<?php

namespace App\Filament\Resources\SplanerResource\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\Splaner;
use App\Filament\Resources\SplanerResource;

class SplanerCalendarWidget extends FullCalendarWidget
{
    public static function canView(): bool
    {
        return true;
    }

    public function getModel(): string
    {
        return Splaner::class;
    }

    public function viewAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\ViewAction::make('view')
            ->model(Splaner::class)
            ->infolist(fn (\Filament\Infolists\Infolist $infolist) => $infolist
                ->schema([
                    \Filament\Infolists\Components\Section::make('Informasi Kegiatan')
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('title')->label('Judul Kegiatan'),
                            \Filament\Infolists\Components\TextEntry::make('location')->label('Lokasi'),
                            \Filament\Infolists\Components\TextEntry::make('start_time')->label('Mulai')->dateTime('d M Y H:i'),
                            \Filament\Infolists\Components\TextEntry::make('end_time')->label('Selesai')->dateTime('d M Y H:i'),
                            \Filament\Infolists\Components\TextEntry::make('status')
                                ->badge()
                                ->colors([
                                    'primary' => 'Dijadwalkan',
                                    'success' => 'Selesai',
                                    'danger' => 'Dibatalkan',
                                ]),
                            \Filament\Infolists\Components\TextEntry::make('description')
                                ->label('Keterangan')
                                ->columnSpanFull()
                                ->html(),
                            \Filament\Infolists\Components\TextEntry::make('suratMasuk.nomor_surat')
                                ->label('Terkait Surat Masuk')
                                ->visible(fn ($record) => $record->surat_masuk_id !== null),
                            \Filament\Infolists\Components\TextEntry::make('suratKeluar.nomor_surat')
                                ->label('Terkait Surat Keluar')
                                ->visible(fn ($record) => $record->surat_keluar_id !== null),
                        ])->columns(2)
                ])
            );
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Splaner::query()
            ->where('start_time', '>=', $fetchInfo['start'])
            ->where('end_time', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Splaner $event) {
                return [
                    'id'    => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_time->toIso8601String(),
                    'end'   => $event->end_time->toIso8601String(),
                    'color' => match($event->status) {
                        'Dijadwalkan' => '#3b82f6', // blue
                        'Selesai' => '#22c55e', // green
                        'Dibatalkan' => '#ef4444', // red
                        default => '#6b7280',
                    },
                ];
            })
            ->toArray();
    }

    public function onEventClick(array $info): void
    {
        $record = Splaner::find($info['id']);
        if ($record) {
            $this->mountAction('view', ['record' => $record]);
        }
    }
}
