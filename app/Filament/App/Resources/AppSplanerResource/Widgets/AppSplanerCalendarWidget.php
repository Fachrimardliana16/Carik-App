<?php

namespace App\Filament\App\Resources\AppSplanerResource\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\Splaner;
use App\Filament\App\Resources\AppSplanerResource;
use Illuminate\Support\Facades\Auth;

class AppSplanerCalendarWidget extends FullCalendarWidget
{
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
                    'url'   => AppSplanerResource::getUrl('edit', ['record' => $event]),
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
}
