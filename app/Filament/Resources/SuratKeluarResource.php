<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratKeluarResource\Pages;
use App\Models\SuratKeluar;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Illuminate\Http\UploadedFile;

class SuratKeluarResource extends Resource
{
    protected static ?string $model = SuratKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    
    protected static ?string $navigationLabel = 'Surat Keluar';
    
    protected static ?string $navigationGroup = 'Persuratan';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->description('Data utama surat keluar')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Section::make('Header Surat')
                                    ->schema([
                                        Forms\Components\Select::make('sifat')
                                            ->label('Sifat Surat')
                                            ->options([
                                                'Biasa' => 'Biasa',
                                                'Segera' => 'Segera',
                                                'Sangat Segera' => 'Sangat Segera',
                                                'Rahasia' => 'Rahasia',
                                            ])->default('Biasa')->required()
                                            ->native(false),
                                              Forms\Components\TextInput::make('nomor_surat')
                                            ->label('Nomor Surat')
                                            ->required()
                                            ->maxLength(255)
                                            ->default('Draft/SK/'.date('Y'))
                                            ->helperText('Nomor akan difinalisasi saat tanda tangan.'),
                                        Forms\Components\TextInput::make('tujuan')
                                            ->label('Kepada Yth')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Nama Pejabat / Instansi Tujuan')
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('klasifikasi_arsip_id')
                                            ->label('Klasifikasi Arsip')
                                            ->relationship('klasifikasiArsip', 'nama')
                                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->kode} - {$record->nama}")
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->native(false),
                                        Forms\Components\Select::make('status_surat_id')
                                            ->label('Status')
                                            ->relationship('statusSurat', 'nama')
                                            ->default(fn () => \App\Models\StatusSurat::where('is_default', true)->first()?->id)
                                            ->required()
                                            ->native(false),
                                        Forms\Components\TextInput::make('perihal')
                                            ->label('Perihal')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Pokok surat')
                                            ->columnSpanFull(),
                                        Forms\Components\DatePicker::make('tanggal_surat')
                                            ->label('Tanggal Surat')
                                            ->required()
                                            ->native(false)
                                            ->default(now()),
                                        Forms\Components\Select::make('penandatangan_id')
                                            ->label('Penandatangan')
                                            ->relationship('penandatangan', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->helperText('Pejabat yang berwenang menandatangani.'),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Isi Surat')
                    ->schema([
                        Forms\Components\Select::make('template_id')
                            ->label('Load Template (Opsional)')
                            ->options(\App\Models\TemplateSurat::pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $template = \App\Models\TemplateSurat::find($state);
                                    if ($template) {
                                        $set('isi_surat', $template->content);
                                    }
                                }
                            })
                            ->columnSpanFull()
                            ->helperText('Pilih template untuk mengisi otomatis isi surat.'),
                            
                        Forms\Components\RichEditor::make('isi_surat')
                            ->label('Isi Surat')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                            ])
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('tembusan')
                            ->label('Tembusan')
                            ->placeholder('Isi daftar tembusan jika ada')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Penandatangan & QR Code')
                    ->schema([
                        Forms\Components\Select::make('penandatangan_id')
                            ->label('Penandatangan')
                            ->options(User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->native(false)
                            ->placeholder('Pilih penandatangan'),
                        Forms\Components\TextInput::make('qr_code')
                            ->label('QR Code')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Akan digenerate otomatis'),
                    ]),

                Forms\Components\Section::make('Lampiran')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Surat')
                            ->directory('surat-keluar')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(5120)
                            ->saveUploadedFileUsing(function (UploadedFile $file) {
                                return \App\Services\FileEncryptionService::encryptAndStore($file, 'surat-keluar');
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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['penandatangan', 'creator', 'updater', 'klasifikasiArsip', 'statusSurat']))
            ->columns([
                Tables\Columns\TextColumn::make('klasifikasiArsip.kode')
                    ->label('Kode')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn ($record) => $record->klasifikasiArsip?->nama),
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tujuan')
                    ->label('Tujuan')
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
                Tables\Columns\TextColumn::make('penandatangan.name')
                    ->label('Penandatangan')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('qr_code')
                    ->label('QR')
                    ->boolean()
                    ->trueIcon('heroicon-o-qr-code')
                    ->falseIcon('heroicon-o-x-mark')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
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
                Tables\Filters\SelectFilter::make('penandatangan_id')
                    ->label('Penandatangan')
                    ->relationship('penandatangan', 'name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

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
                        ->action(function (SuratKeluar $record, array $data) {
                            $record->splaners()->create([
                                'title' => $data['title'],
                                'start_time' => $data['start_time'],
                                'end_time' => $data['end_time'],
                                'location' => $data['location'],
                                // 'user_id' => auth()->id(), // created_by handled by Model trait if exists, or Splaner observer. 
                                // Splaner uses HasAuditColumns trait but 'created_by' is what it uses.
                                // Splaner::create with user() relation needs created_by.
                                'created_by' => auth()->id(),
                                'status' => 'Dijadwalkan',
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Jadwal Berhasil Ditambahkan ke S-Planer')
                                ->success()
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('sign')
                        ->label('Sign Document')
                        ->icon('heroicon-o-pencil-square')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(function (SuratKeluar $record) {
                            $isSigner = $record->penandatangan_id === auth()->id();
                            $isDelegated = \App\Services\DelegationService::checkDelegation($record->penandatangan_id, auth()->id());
                            
                            return $record->status === 'Menunggu TTD' && 
                                   ($isSigner || $isDelegated) &&
                                   !$record->signed_at;
                        })
                        ->action(function (SuratKeluar $record) {
                            $isDelegated = \App\Services\DelegationService::checkDelegation($record->penandatangan_id, auth()->id());
                            
                            // If delegated, use the delegate's key but maybe mark as "an."?
                            // For digital signature, we MUST use the key of the person actually signing (Authentication principle).
                            // So we sign with auth()->user()'s key.
                            
                            $signer = auth()->user();
                            $dataToSign = $record->nomor_surat . '|' . $record->tanggal_surat->format('Y-m-d') . '|' . $record->perihal;
                            
                            // Add extra context if delegated
                            if ($isDelegated) {
                                $dataToSign .= '|Plh:' . $signer->name;
                            }
                            
                            $signature = \App\Services\DigitalSignatureService::sign($signer, $dataToSign);
                            
                            if (!$signature) {
                                $msg = $isDelegated ? 'Anda (Plh) belum memiliki Digital Signature Key.' : 'Anda belum memiliki Digital Signature Key.';
                                Notification::make()
                                    ->title('Gagal menandatangani')
                                    ->body($msg . ' Hubungi Admin.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Update logic: if Plh, maybe we should update who actually signed?
                            // We can use updated_by or add 'actual_signer_id' column.
                            // For simplicity, we trust the signature verification which uses the public key.
                            
                            $record->update([
                                'signature_hash' => $signature,
                                'signed_at' => now(),
                                'status' => 'Selesai',
                            ]);
                            
                            // If Plh, add note
                            if ($isDelegated) {
                                // Add activity log or note? Activity log handles it via updated_by.
                                Notification::make()
                                    ->title('Dokumen Berhasil Ditandatangani (Plh)')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Dokumen Berhasil Ditandatangani')
                                    ->success()
                                    ->send();
                            }
                        }),

                    Tables\Actions\Action::make('download_decrypted')
                        ->label('Cetak Surat (Decrypted)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn ($record) => $record->file_path ? route('file.download', ['path' => $record->file_path]) : null)
                        ->openUrlInNewTab()
                        ->visible(fn ($record) => $record->file_path),

                    Tables\Actions\Action::make('download_pdf')
                        ->label('Cetak Surat')
                        ->icon('heroicon-o-printer')
                        ->color('gray')
                        ->action(function (SuratKeluar $record) {
                            return \App\Services\PdfService::printSuratKeluar($record);
                        }),

                    Tables\Actions\Action::make('share_wa')
                        ->label('Share ke WhatsApp')
                        ->icon('heroicon-o-share')
                        ->color('success')
                        ->url(fn ($record) => 'https://wa.me/?text=' . urlencode('Ada Surat Himbauan/Dinas Baru. Silakan cek aplikasi SIPD.'))
                        ->openUrlInNewTab(),

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
                        }),
                ]),
            ])
            ->defaultSort('tanggal_surat', 'desc');
    }

    public static function getWidgets(): array
    {
        return [
            SuratKeluarResource\Widgets\SuratKeluarStats::class,
        ];
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
            'index' => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'view' => Pages\ViewSuratKeluar::route('/{record}'),
            'edit' => Pages\EditSuratKeluar::route('/{record}/edit'),
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
