<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\Splaner;

class UserDashboardStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        return [
            Stat::make('Surat Masuk Saya', SuratMasuk::where('created_by', $userId)->count())
                ->description('Surat masuk yang saya buat')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('success'),
            
            Stat::make('Surat Keluar Saya', SuratKeluar::where('created_by', $userId)->count())
                ->description('Surat keluar yang saya buat')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('primary'),

            Stat::make('Disposisi Masuk', Disposisi::where('kepada_user_id', $userId)->count())
                ->description('Disposisi ditujukan ke saya')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('warning'),

            Stat::make('Agenda Saya', Splaner::where('created_by', $userId)->where('status', 'Dijadwalkan')->count())
                ->description('Agenda saya yang akan datang')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
