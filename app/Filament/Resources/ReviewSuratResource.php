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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('surat_keluar_id')
                    ->relationship('suratKeluar', 'nomor_surat')
                    ->required(),
                Forms\Components\Select::make('reviewer_id')
                    ->relationship('reviewer', 'name')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                        'Revisi' => 'Revisi',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('suratKeluar.nomor_surat')->label('No. Surat')->searchable(),
                Tables\Columns\TextColumn::make('reviewer.name')->label('Reviewer'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Disetujui',
                        'danger' => 'Ditolak',
                        'info' => 'Revisi',
                    ]),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
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
