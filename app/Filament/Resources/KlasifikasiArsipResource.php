<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KlasifikasiArsipResource\Pages;
use App\Filament\Resources\KlasifikasiArsipResource\RelationManagers;
use App\Models\KlasifikasiArsip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KlasifikasiArsipResource extends Resource
{
    protected static ?string $model = KlasifikasiArsip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Induk Klasifikasi')
                            ->relationship('parent', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->kode} - {$record->nama}")
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Klasifikasi')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Klasifikasi')
                            ->required(),
                        Forms\Components\TextInput::make('level')
                            ->numeric()
                            ->default(1)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Textarea::make('keterangan')
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Klasifikasi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('parent.kode')
                    ->label('Induk')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'primary',
                        2 => 'success',
                        3 => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKlasifikasiArsips::route('/'),
            'create' => Pages\CreateKlasifikasiArsip::route('/create'),
            'view' => Pages\ViewKlasifikasiArsip::route('/{record}'),
            'edit' => Pages\EditKlasifikasiArsip::route('/{record}/edit'),
        ];
    }
}
