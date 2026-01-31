<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TembusanResource\Pages;
use App\Models\Tembusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TembusanResource extends Resource
{
    protected static ?string $model = Tembusan::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Buku Bantu';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kontak Tembusan')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('nama_tujuan')
                                    ->label('Nama Jabatan / Instansi')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('alamat')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('keterangan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_tujuan')
                    ->label('Nama Jabatan / Instansi')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
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
            TembusanResource\Widgets\TembusanStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTembusans::route('/'),
            'create' => Pages\CreateTembusan::route('/create'),
            'edit' => Pages\EditTembusan::route('/{record}/edit'),
        ];
    }
}
