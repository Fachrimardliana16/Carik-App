<?php

namespace App\Filament\Resources\SplanerResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SplanerStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Agenda', \App\Models\Splaner::count())
                ->description('Total seluruh kegiatan')
                ->icon('heroicon-o-calendar')
                ->color('primary'),
            Stat::make('Agenda Hari Ini', \App\Models\Splaner::whereDate('start_time', now())->count())
                ->description('Kegiatan pada hari ini')
                ->icon('heroicon-o-calendar-days')
                ->color('info'),
            Stat::make('Selesai', \App\Models\Splaner::where('status', 'Selesai')->count())
                ->description('Kegiatan yang telah terlaksana')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
