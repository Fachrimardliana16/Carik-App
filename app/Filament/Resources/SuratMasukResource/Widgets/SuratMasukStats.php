<?php

namespace App\Filament\Resources\SuratMasukResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuratMasukStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['super_admin', 'admin']);

        // Query helpers
        $queryTotal = \App\Models\SuratMasuk::query();
        $queryToday = \App\Models\SuratMasuk::whereDate('tanggal_diterima', now());
        if (!$isAdmin) {
            $filter = function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('tujuan_user_id', $user->id)
                  ->orWhereHas('disposisis', fn ($d) => $d->where('kepada_user_id', $user->id));
            };
            $queryTotal->where($filter);
            $queryToday->where($filter);

            // Action Needed for User:
            // 1. Letters addressed to user (Tujuan) that are NOT 'Diterima' (Pending)
            // 2. Dispositions to user that are 'Pending'
            $queryAction = \App\Models\SuratMasuk::where(function ($q) use ($user) {
                // Case 1: Direct recipient, status not accepted
                $q->where('tujuan_user_id', $user->id)
                  ->where('status', '!=', 'Diterima')
                  ->where('status', '!=', 'Selesai'); // Also exclude completed items
            })->orWhereHas('disposisis', function ($d) use ($user) {
                // Case 2: Disposition recipient, status Pending
                $d->where('kepada_user_id', $user->id)
                  ->where('status', 'Pending');
            });
        } else {
            // Action Needed for Admin: Draft or Review status
            $queryAction = \App\Models\SuratMasuk::whereHas('statusSurat', fn($q) => $q->whereIn('nama', ['Draft', 'Review']));
        }

        return [
            Stat::make('Total Surat Masuk', $queryTotal->count())
                ->description('Total keseluruhan data')
                ->icon('heroicon-o-inbox-arrow-down')
                ->color('primary'),
            Stat::make('Surat Hari Ini', $queryToday->count())
                ->description('Data masuk hari ini')
                ->icon('heroicon-o-calendar')
                ->color('info'),
            Stat::make('Butuh Tindak Lanjut', $queryAction->count())
                ->description('Menunggu proses selanjutnya')
                ->icon('heroicon-o-exclamation-circle')
                ->color('warning'),
        ];
    }
}
