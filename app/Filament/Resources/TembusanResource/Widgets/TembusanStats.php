<?php

namespace App\Filament\Resources\TembusanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TembusanStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tembusan', \App\Models\Tembusan::count())
                ->description('Total kontak di buku bantu')
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Punya Email', \App\Models\Tembusan::whereNotNull('email')->count())
                ->description('Kontak dengan alamat email')
                ->icon('heroicon-o-at-symbol')
                ->color('info'),
            Stat::make('Punya Alamat', \App\Models\Tembusan::whereNotNull('alamat')->count())
                ->description('Kontak dengan alamat fisik')
                ->icon('heroicon-o-map-pin')
                ->color('success'),
        ];
    }
}
