<?php

namespace App\Filament\Resources\SuratMasukResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuratMasukStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Surat Masuk', \App\Models\SuratMasuk::count())
                ->description('Total keseluruhan data')
                ->icon('heroicon-o-inbox-arrow-down')
                ->color('primary'),
            Stat::make('Surat Hari Ini', \App\Models\SuratMasuk::whereDate('tanggal_diterima', now())->count())
                ->description('Data masuk hari ini')
                ->icon('heroicon-o-calendar')
                ->color('info'),
            Stat::make('Butuh Tindak Lanjut', \App\Models\SuratMasuk::whereHas('statusSurat', fn($q) => $q->where('nama', 'Draft')->orWhere('nama', 'Review'))->count())
                ->description('Menunggu proses selanjutnya')
                ->icon('heroicon-o-exclamation-circle')
                ->color('warning'),
        ];
    }
}
