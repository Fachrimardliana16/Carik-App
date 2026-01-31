<?php

namespace App\Filament\Resources\ReviewSuratResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewSuratStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Review', \App\Models\ReviewSurat::count())
                ->description('Total keseluruhan data review')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('primary'),
            Stat::make('Pending Review', \App\Models\ReviewSurat::where('status', 'Pending')->count())
                ->description('Menunggu tinjauan')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Disetujui', \App\Models\ReviewSurat::where('status', 'Disetujui')->count())
                ->description('Review yang telah disetujui')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
