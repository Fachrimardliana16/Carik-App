<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratMasukResource\Pages;
use App\Models\SuratMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Http\UploadedFile;

class SuratMasukResource extends Resource
{
    protected static ?string $model = SuratMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    
    protected static ?string $navigationLabel = 'Surat Masuk';
    
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Surat')
                    ->description('Data utama surat masuk')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nomor_agenda')
                                    ->label('Nomor Agenda')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('AGD-YYYYMMDD...')
                                    ->default(fn () => 'AGD-' . date('YmdHis'))
                                    ->helperText('Nomor pencatatan sistem.'),
                                Forms\Components\TextInput::make('nomor_surat')
                                    ->label('Nomor Surat')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('Contoh: 005/DK/X/2023')
                                    ->helperText('Nomor yang tertera pada surat fisik.'),
                                Forms\Components\Select::make('klasifikasi_arsip_id')
                                    ->label('Klasifikasi Arsip')
                                    ->relationship('klasifikasiArsip', 'nama')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->kode} - {$record->nama}")
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->native(false)
                                    ->columnSpanFull(),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_surat')
                                    ->label('Tanggal Surat')
                                    ->required()
                                    ->maxDate(now())
                                    ->native(false),
                                Forms\Components\DatePicker::make('tanggal_diterima')
                                    ->label('Tanggal Diterima')
                                    ->required()
                                    ->default(now())
                                    ->native(false),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Surat')
                    ->schema([
                        Forms\Components\TextInput::make('pengirim')
                            ->label('Pengirim')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama Instansi / Pengirim'),
                        Forms\Components\TextInput::make('perihal')
                            ->label('Perihal')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Pokok isi surat'),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('sifat')
                                    ->label('Sifat Surat')
                                    ->options([
                                        'Biasa' => 'Biasa',
                                        'Segera' => 'Segera',
                                        'Sangat Segera' => 'Sangat Segera',
                                        'Rahasia' => 'Rahasia',
                                    ])
                                    ->required()
                                    ->native(false),
                                Forms\Components\Hidden::make('status_surat_id')
                                    ->default(fn () => \App\Models\StatusSurat::where('nama', 'Sent')->first()?->id ?? \App\Models\StatusSurat::where('is_default', true)->first()?->id),
                            ]),
                        Forms\Components\Textarea::make('isi_ringkas')
                            ->label('Isi Ringkas')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Ringkasan isi surat...'),
                    ]),

                Forms\Components\Section::make('Lampiran')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Surat')
                            ->directory('surat-masuk')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(10240)
                            ->saveUploadedFileUsing(function (UploadedFile $file) {
                                return \App\Services\FileEncryptionService::encryptAndStore($file, 'surat-masuk');
                            })
                            ->downloadable()
                            ->openable()
                            ->previewable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['creator', 'updater', 'klasifikasiArsip', 'statusSurat']))
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('klasifikasiArsip.kode')
                    ->label('Kode Klasifikasi')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn ($record) => $record->klasifikasiArsip?->nama),
                Tables\Columns\TextColumn::make('nomor_agenda')
                    ->label('No. Agenda')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->label('Tgl. Surat')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_diterima')
                    ->label('Tgl. Diterima')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pengirim')
                    ->label('Pengirim')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable()
                    ->limit(40)
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('sifat')
                    ->label('Sifat')
                    ->colors([
                        'success' => 'Biasa',
                        'warning' => 'Segera',
                        'danger' => 'Sangat Segera',
                        'gray' => 'Rahasia',
                    ]),
                Tables\Columns\TextColumn::make('statusSurat.nama')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record->statusSurat?->warna ?? 'gray'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_surat_id')
                    ->label('Status')
                    ->relationship('statusSurat', 'nama'),
                Tables\Filters\SelectFilter::make('klasifikasi_arsip_id')
                    ->label('Klasifikasi')
                    ->relationship('klasifikasiArsip', 'nama'),
                Tables\Filters\SelectFilter::make('sifat')
                    ->label('Sifat')
                    ->options([
                        'Biasa' => 'Biasa',
                        'Segera' => 'Segera',
                        'Sangat Segera' => 'Sangat Segera',
                        'Rahasia' => 'Rahasia',
                    ]),
                Tables\Filters\Filter::make('tanggal_surat')
                    ->form([
                        Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_surat', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_surat', '<=', $date),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('terima')
                        ->label('Terima')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->statusSurat?->nama === 'Draft')
                        ->form([
                            Forms\Components\Select::make('klasifikasi_arsip_id')
                                ->label('Klasifikasi Arsip')
                                ->relationship('klasifikasiArsip', 'nama')
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->kode} - {$record->nama}")
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('sifat')
                                ->label('Sifat Surat')
                                ->options([
                                    'Biasa' => 'Biasa',
                                    'Segera' => 'Segera',
                                    'Sangat Segera' => 'Sangat Segera',
                                    'Rahasia' => 'Rahasia',
                                ])
                                ->required(),
                            Forms\Components\Select::make('status_surat_id')
                                ->label('Status Baru')
                                ->relationship('statusSurat', 'nama')
                                ->default(fn () => \App\Models\StatusSurat::where('nama', '!=', 'Draft')->where('is_default', true)->first()?->id)
                                ->required(),
                        ])
                        ->action(function (SuratMasuk $record, array $data) {
                            $record->update($data);
                            \Filament\Notifications\Notification::make()
                                ->title('Surat Berhasil Diterima')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('tolak')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->statusSurat?->nama === 'Draft')
                        ->requiresConfirmation()
                        ->action(function (SuratMasuk $record) {
                            $statusArchived = \App\Models\StatusSurat::where('nama', 'Archived')->first();
                            $record->update([
                                'status_surat_id' => $statusArchived?->id,
                                'status' => 'Selesai',
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Surat Ditolak & Diarsipkan')
                                ->danger()
                                ->send();
                        }),
                    Tables\Actions\Action::make('buat_disposisi')
                        ->label('Buat Disposisi')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('kepada_user_id')
                                ->label('Tujuan Disposisi')
                                ->relationship('disposisis.kepadaUser', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Textarea::make('instruksi')
                                ->label('Instruksi')
                                ->required(),
                            Forms\Components\Select::make('prioritas')
                                ->label('Prioritas')
                                ->options([
                                    'Biasa' => 'Biasa',
                                    'Penting' => 'Penting',
                                    'Segera' => 'Segera',
                                    'Sangat Segera' => 'Sangat Segera',
                                ])
                                ->default('Biasa')
                                ->required(),
                            Forms\Components\DatePicker::make('batas_waktu')
                                ->label('Batas Waktu'),
                        ])
                        ->action(function (SuratMasuk $record, array $data) {
                            $record->disposisis()->create([
                                'dari_user_id' => auth()->id(),
                                'kepada_user_id' => $data['kepada_user_id'],
                                'instruksi' => $data['instruksi'],
                                'prioritas' => $data['prioritas'],
                                'batas_waktu' => $data['batas_waktu'],
                                'status' => 'Pending',
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Disposisi Berhasil Dibuat')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('buat_jadwal')
                        ->label('Buat Jadwal (Planer)')
                        ->icon('heroicon-o-calendar')
                        ->color('warning')
                        ->visible(fn ($record) => !$record->splaners()->exists())
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->label('Judul Kegiatan')
                                ->default(fn ($record) => $record->perihal)
                                ->required(),
                            Forms\Components\DateTimePicker::make('start_time')
                                ->label('Waktu Mulai')
                                ->required(),
                            Forms\Components\DateTimePicker::make('end_time')
                                ->label('Waktu Selesai')
                                ->required(),
                            Forms\Components\TextInput::make('location')
                                ->label('Lokasi'),
                        ])
                        ->action(function (SuratMasuk $record, array $data) {
                            $record->splaners()->create([
                                'title' => $data['title'],
                                'start_time' => $data['start_time'],
                                'end_time' => $data['end_time'],
                                'location' => $data['location'],
                                'user_id' => auth()->id(),
                                'status' => 'Dijadwalkan',
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Jadwal Berhasil Ditambahkan ke S-Planer')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('download_file')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn ($record) => $record->file_path ? route('file.download', ['path' => $record->file_path]) : null)
                        ->openUrlInNewTab()
                        ->visible(fn ($record) => $record->file_path),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export ke PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // Logic to export these $records as PDF
                            // For simplicity, we might need a multi-page PDF generator
                        }),
                ]),
            ])
            ->defaultSort('tanggal_diterima', 'desc');
    }

    public static function getWidgets(): array
    {
        return [
            SuratMasukResource\Widgets\SuratMasukStats::class,
        ];
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Informasi Surat')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('nomor_surat'),
                        \Filament\Infolists\Components\TextEntry::make('nomor_agenda'),
                        \Filament\Infolists\Components\TextEntry::make('pengirim'),
                        \Filament\Infolists\Components\TextEntry::make('perihal'),
                        \Filament\Infolists\Components\TextEntry::make('tanggal_diterima')->date('d M Y'),
                        \Filament\Infolists\Components\TextEntry::make('sifat')
                            ->badge()
                            ->colors([
                                'success' => 'Biasa',
                                'warning' => 'Segera',
                                'danger' => 'Sangat Segera',
                                'gray' => 'Rahasia',
                            ]),
                        \Filament\Infolists\Components\TextEntry::make('isi_ringkas')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                \Filament\Infolists\Components\Section::make('Timeline & Disposisi')
                    ->schema([
                         \Filament\Infolists\Components\ViewEntry::make('timeline')
                            ->view('filament.surat-timeline')
                            ->columnSpanFull(),
                    ]),

                \Filament\Infolists\Components\Section::make('Daftar Disposisi')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('disposisis')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('kepadaUser.name')->label('Kepada'),
                                \Filament\Infolists\Components\TextEntry::make('instruksi')->html(),
                                \Filament\Infolists\Components\TextEntry::make('status')->badge(),
                                \Filament\Infolists\Components\TextEntry::make('created_at')->label('Tanggal')->dateTime(),
                            ])
                            ->columns(4)
                    ])
                    ->collapsible(),

                \Filament\Infolists\Components\Section::make('Jadwal Terkait (S-Planer)')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('splaners')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('title')->label('Kegiatan'),
                                \Filament\Infolists\Components\TextEntry::make('start_time')->label('Mulai')->dateTime(),
                                \Filament\Infolists\Components\TextEntry::make('location')->label('Lokasi'),
                                \Filament\Infolists\Components\TextEntry::make('status')->badge(),
                            ])
                            ->columns(4)
                    ])
                    ->collapsible()
                    ->visible(fn ($record) => $record->splaners()->exists()),
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
            'index' => Pages\ListSuratMasuks::route('/'),
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'view' => Pages\ViewSuratMasuk::route('/{record}'),
            'edit' => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
