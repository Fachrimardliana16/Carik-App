<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewSuratResource\Pages;
use App\Models\ReviewSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewSuratResource extends Resource
{
    protected static ?string $model = ReviewSurat::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Surat')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('surat_keluar_id')
                                    ->relationship('suratKeluar', 'nomor_surat')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('reviewer_id')
                                    ->relationship('reviewer', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'Pending' => 'Pending',
                                        'Disetujui' => 'Disetujui',
                                        'Ditolak' => 'Ditolak',
                                        'Revisi' => 'Revisi',
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),
                        Forms\Components\Textarea::make('catatan')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('suratKeluar.nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Reviewer')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Disetujui',
                        'danger' => 'Ditolak',
                        'info' => 'Revisi',
                    ]),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                        'Revisi' => 'Revisi',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export ke PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // Logic to export these $records as PDF
                        }),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            ReviewSuratResource\Widgets\ReviewSuratStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviewSurats::route('/'),
            'create' => Pages\CreateReviewSurat::route('/create'),
            'edit' => Pages\EditReviewSurat::route('/{record}/edit'),
        ];
    }
}
