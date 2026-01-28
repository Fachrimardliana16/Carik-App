<?php

namespace App\Filament\App\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Splaner;

class UserUpcomingAgendaWidget extends BaseWidget
{
    protected static ?string $heading = 'Agenda Saya Terdekat';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Splaner::query()
                    ->where('created_by', auth()->id())
                    ->where('status', 'Dijadwalkan')
                    ->where('start_time', '>=', now())
                    ->orderBy('start_time', 'asc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(['primary' => 'Dijadwalkan']),
            ]);
    }
}
