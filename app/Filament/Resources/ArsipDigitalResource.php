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
                Forms\Components\Section::make('Informasi Arsip')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('judul')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: SK Pengangkatan Pegawai'),
                                Forms\Components\TextInput::make('nomor_arsip')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('Contoh: ARS-2026-001'),
                                Forms\Components\Select::make('kategori')
                                    ->options([
                                        'SK' => 'SK',
                                        'Peraturan' => 'Peraturan',
                                        'Notulen' => 'Notulen',
                                        'Laporan' => 'Laporan',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->required()
                                    ->native(false),
                                Forms\Components\DatePicker::make('tanggal_arsip')
                                    ->required()
                                    ->native(false),
                            ]),
                        Forms\Components\Textarea::make('deskripsi')
                            ->rows(3)
                            ->placeholder('Deskripsi singkat tentang isi arsip...')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('File & Lampiran')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Arsip')
                            ->directory('arsip')
                            ->required()
                            ->saveUploadedFileUsing(function (\Illuminate\Http\UploadedFile $file) {
                                return \App\Services\FileEncryptionService::encryptAndStore($file, 'arsip');
                            })
                            ->openable()
                            ->downloadable(),
                        Forms\Components\Hidden::make('uploaded_by')
                            ->default(Auth::id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_arsip')
                    ->label('No. Arsip')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('kategori')
                    ->colors([
                        'primary' => 'SK',
                        'success' => 'Peraturan',
                        'warning' => 'Notulen',
                        'info' => 'Laporan',
                        'gray' => 'Lainnya',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_arsip')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('uploadedBy.name')
                    ->label('Diupload Oleh')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'SK' => 'SK',
                        'Peraturan' => 'Peraturan',
                        'Notulen' => 'Notulen',
                        'Laporan' => 'Laporan',
                        'Lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('download')
                        ->label('Download (Decrypted)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn (ArsipDigital $record) => $record->file_path ? route('file.download', ['path' => $record->file_path]) : null)
                        ->openUrlInNewTab(),
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
            ArsipDigitalResource\Widgets\ArsipDigitalStats::class,
        ];
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
