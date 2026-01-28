<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArsipDigitalResource\Pages;
use App\Models\ArsipDigital;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ArsipDigitalResource extends Resource
{
    protected static ?string $model = ArsipDigital::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Agenda & Arsip';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')->required(),
                Forms\Components\TextInput::make('nomor_arsip')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('kategori')
                    ->options([
                        'SK' => 'SK',
                        'Peraturan' => 'Peraturan',
                        'Notulen' => 'Notulen',
                        'Laporan' => 'Laporan',
                        'Lainnya' => 'Lainnya',
                    ])->required(),
                Forms\Components\DatePicker::make('tanggal_arsip')->required(),
                Forms\Components\FileUpload::make('file_path')
                    ->directory('arsip')
                    ->required()
                    ->openable()
                    ->downloadable(),
                Forms\Components\Textarea::make('deskripsi')->columnSpanFull(),
                Forms\Components\Hidden::make('uploaded_by')->default(Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_arsip')->searchable(),
                Tables\Columns\TextColumn::make('judul')->searchable(),
                Tables\Columns\TextColumn::make('kategori')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_arsip')->date(),
                Tables\Columns\TextColumn::make('uploadedBy.name')->label('Diupload Oleh'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (ArsipDigital $record) => \Illuminate\Support\Facades\Storage::url($record->file_path))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArsipDigitals::route('/'),
            'create' => Pages\CreateArsipDigital::route('/create'),
            'edit' => Pages\EditArsipDigital::route('/{record}/edit'),
        ];
    }
}
