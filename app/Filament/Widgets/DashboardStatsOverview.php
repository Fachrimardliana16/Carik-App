<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\Splaner;

class DashboardStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Surat Masuk', SuratMasuk::count())
                ->description('Semua surat masuk tercatat')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total Surat Keluar', SuratKeluar::count())
                ->description('Semua surat keluar tercatat')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('primary')
                ->chart([3, 5, 12, 14, 2, 10, 5]),

            Stat::make('Total Disposisi', Disposisi::count())
                ->description('Disposisi aktif & selesai')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('warning'),

            Stat::make('Agenda Kegiatan', Splaner::where('status', 'Dijadwalkan')->count())
                ->description('Agenda yang akan datang')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
