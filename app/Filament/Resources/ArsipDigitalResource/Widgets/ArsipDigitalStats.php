<?php

namespace App\Filament\Resources\ArsipDigitalResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ArsipDigitalStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Arsip', \App\Models\ArsipDigital::count())
                ->description('Total dokumen terarsip')
                ->icon('heroicon-o-archive-box')
                ->color('primary'),
            Stat::make('Kategori SK', \App\Models\ArsipDigital::where('kategori', 'SK')->count())
                ->description('Surat Keputusan')
                ->icon('heroicon-o-document-text')
                ->color('info'),
            Stat::make('Arsip Tahun Ini', \App\Models\ArsipDigital::whereYear('tanggal_arsip', now()->year)->count())
                ->description('Data diarsipkan tahun ini')
                ->icon('heroicon-o-calendar')
                ->color('success'),
        ];
    }
}
