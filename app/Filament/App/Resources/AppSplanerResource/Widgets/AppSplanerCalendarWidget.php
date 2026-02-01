<?php

namespace App\Filament\App\Resources\AppSplanerResource\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\Splaner;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class AppSplanerCalendarWidget extends FullCalendarWidget
{
    public function getModel(): string
    {
        return Splaner::class;
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Splaner::query()
            ->where('user_id', Auth::id())
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

    public function viewAction(): ViewAction
    {
        return ViewAction::make('view')
            ->model(Splaner::class)
            ->infolist(fn (Infolist $infolist) => $infolist
                ->schema([
                    Section::make('Informasi Kegiatan')
                        ->schema([
                            TextEntry::make('title')->label('Judul Kegiatan'),
                            TextEntry::make('location')->label('Lokasi'),
                            TextEntry::make('start_time')->label('Mulai')->dateTime('d M Y H:i'),
                            TextEntry::make('end_time')->label('Selesai')->dateTime('d M Y H:i'),
                            TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Dijadwalkan' => 'primary',
                                    'Selesai' => 'success',
                                    'Dibatalkan' => 'danger',
                                    default => 'gray',
                                }),
                            TextEntry::make('description')
                                ->label('Keterangan')
                                ->columnSpanFull()
                                ->html(),
                        ])->columns(2)
                ])
            );
    }

    /**
     * Mark as complete action for users
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('selesaikan')
                ->label('Tandai Selesai')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Penyelesaian')
                ->modalDescription('Apakah Anda yakin kegiatan ini sudah selesai?')
                ->action(function (array $arguments) {
                    $record = Splaner::find($arguments['record'] ?? null);
                    if ($record && $record->user_id === Auth::id()) {
                        $record->update(['status' => 'Selesai']);
                    }
                })
                ->visible(fn () => Auth::user()->hasAnyRole(['staff', 'kepala_bagian', 'kepala_sub_bagian'])),
        ];
    }

    public function onEventClick(array $info): void
    {
        $record = Splaner::find($info['id']);
        if ($record) {
            $this->mountAction('view', ['record' => $record]);
        }
    }
}
