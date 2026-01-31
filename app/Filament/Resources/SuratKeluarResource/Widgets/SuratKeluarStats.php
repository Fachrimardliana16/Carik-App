<?php

namespace App\Filament\Resources\SuratKeluarResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuratKeluarStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Surat Keluar', \App\Models\SuratKeluar::count())
                ->description('Total keseluruhan data')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary'),
            Stat::make('Belum Tanda Tangan', \App\Models\SuratKeluar::whereHas('statusSurat', fn($q) => $q->where('nama', 'Draft')->orWhere('nama', 'Review'))->count())
                ->description('Menunggu persetujuan / TTE')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Sudah Dikirim', \App\Models\SuratKeluar::whereHas('statusSurat', fn($q) => $q->where('nama', 'Sent'))->count())
                ->description('Data sudah terkirim')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
