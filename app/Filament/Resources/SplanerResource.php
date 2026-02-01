<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SplanerResource\Pages;
use App\Models\Splaner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\SplanerResource\Widgets\SplanerCalendarWidget;

class SplanerResource extends Resource
{
    protected static ?string $model = Splaner::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Agenda & Arsip';
    protected static ?string $navigationLabel = 'S-Planer (Agenda)';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('start_time', now())->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Agenda')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Kegiatan')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Rapat Koordinasi Dinas')
                                    ->columnSpanFull(),
                                Forms\Components\DateTimePicker::make('start_time')
                                    ->label('Waktu Mulai')
                                    ->required()
                                    ->native(false),
                                Forms\Components\DateTimePicker::make('end_time')
                                    ->label('Waktu Selesai')
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('location')
                                    ->label('Lokasi')
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Ruang Rapat Utama / Via Zoom'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'Dijadwalkan' => 'Dijadwalkan',
                                        'Selesai' => 'Selesai',
                                        'Dibatalkan' => 'Dibatalkan',
                                    ])
                                    ->default('Dijadwalkan')
                                    ->required()
                                    ->native(false),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi / Catatan')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Detail kegiatan, link meeting, atau catatan lainnya...'),
                        Forms\Components\Hidden::make('user_id')
                            ->default(Auth::id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'Dijadwalkan',
                        'success' => 'Selesai',
                        'danger' => 'Dibatalkan',
                    ]),
            ])
            ->defaultSort('start_time', 'asc')
            ->recordAction(Tables\Actions\ViewAction::class)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('mark_as_selesai')
                        ->label('Selesai')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'Dijadwalkan')
                        ->action(function (Splaner $record) {
                            $record->update(['status' => 'Selesai']);
                            \Filament\Notifications\Notification::make()
                                ->title('Kegiatan Selesai')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('report_pdf')
                    ->label('Report PDF')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('danger')
                    ->action(function () {
                        return \App\Services\PdfService::printSplanerReport();
                    })
            ]);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Informasi Kegiatan')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('title')->label('Judul Kegiatan'),
                        \Filament\Infolists\Components\TextEntry::make('location')->label('Lokasi'),
                        \Filament\Infolists\Components\TextEntry::make('start_time')->label('Mulai')->dateTime('d M Y H:i'),
                        \Filament\Infolists\Components\TextEntry::make('end_time')->label('Selesai')->dateTime('d M Y H:i'),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->colors([
                                'primary' => 'Dijadwalkan',
                                'success' => 'Selesai',
                                'danger' => 'Dibatalkan',
                            ]),
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->label('Keterangan')
                            ->columnSpanFull()
                            ->html(),
                        \Filament\Infolists\Components\TextEntry::make('suratMasuk.nomor_surat')
                            ->label('Terkait Surat Masuk')
                            ->visible(fn ($record) => $record->surat_masuk_id !== null),
                        \Filament\Infolists\Components\TextEntry::make('suratKeluar.nomor_surat')
                            ->label('Terkait Surat Keluar')
                            ->visible(fn ($record) => $record->surat_keluar_id !== null),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSplaners::route('/'),
            'create' => Pages\CreateSplaner::route('/create'),
            'edit' => Pages\EditSplaner::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            SplanerCalendarWidget::class,
            SplanerResource\Widgets\SplanerStats::class,
        ];
    }
}
