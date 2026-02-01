<?php

namespace App\Filament\Resources\SuratKeluarResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuratKeluarStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['super_admin', 'admin']);

        $queryTotal = \App\Models\SuratKeluar::query();
        $queryDraft = \App\Models\SuratKeluar::whereHas('statusSurat', fn($q) => $q->where('nama', 'Draft')->orWhere('nama', 'Review'));
        $querySent = \App\Models\SuratKeluar::whereHas('statusSurat', fn($q) => $q->where('nama', 'Sent'));

        if (!$isAdmin) {
             $queryTotal->where('created_by', $user->id);
             $queryDraft->where('created_by', $user->id);
             $querySent->where('created_by', $user->id);
        }

        return [
            Stat::make('Total Surat Keluar', $queryTotal->count())
                ->description('Total keseluruhan data')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary'),
            Stat::make('Belum Tanda Tangan', $queryDraft->count())
                ->description('Menunggu persetujuan / TTE')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Sudah Dikirim', $querySent->count())
                ->description('Data sudah terkirim')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
