<?php

namespace App\Filament\Resources\DisposisiResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DisposisiStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['super_admin', 'admin']);

        $queryTotal = \App\Models\Disposisi::query();
        $queryPending = \App\Models\Disposisi::where('status', 'Pending');
        $querySelesai = \App\Models\Disposisi::where('status', 'Selesai');

        if (!$isAdmin) {
             // Users see dispositions assigned TO them or created BY them
             $filter = function ($q) use ($user) {
                 $q->where('kepada_user_id', $user->id)
                   ->orWhere('dari_user_id', $user->id);
             };
             $queryTotal->where($filter);
             $queryPending->where($filter);
             $querySelesai->where($filter);
        }

        return [
            Stat::make('Total Disposisi', $queryTotal->count())
                ->description('Total keseluruhan disposisi')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('primary'),
            Stat::make('Pending', $queryPending->count())
                ->description('Menunggu tindak lanjut')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Selesai', $querySelesai->count())
                ->description('Disposisi telah diselesaikan')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
