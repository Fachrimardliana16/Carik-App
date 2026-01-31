<?php

namespace App\Filament\Resources\DisposisiResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DisposisiStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Disposisi', \App\Models\Disposisi::count())
                ->description('Total keseluruhan disposisi')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('primary'),
            Stat::make('Pending', \App\Models\Disposisi::where('status', 'Pending')->count())
                ->description('Menunggu tindak lanjut')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Selesai', \App\Models\Disposisi::where('status', 'Selesai')->count())
                ->description('Disposisi telah diselesaikan')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
