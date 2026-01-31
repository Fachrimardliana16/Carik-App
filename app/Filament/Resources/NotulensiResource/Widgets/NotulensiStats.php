<?php

namespace App\Filament\Resources\NotulensiResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NotulensiStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Berita Acara / Notulensi', \App\Models\Notulensi::count())
                ->description('Total keseluruhan data')
                ->icon('heroicon-o-document-text')
                ->color('primary'),
            Stat::make('Belum Disetujui', \App\Models\Notulensi::where('status', 'Pending Approval')->count())
                ->description('Menunggu persetujuan pimpinan')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Telah Disetujui', \App\Models\Notulensi::where('status', 'Approved')->orWhere('status', 'Forwarded')->count())
                ->description('Data sudah final')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
