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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereHas('statusSurat', fn ($q) => $q->where('nama', 'Sent'))->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

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
                                    ->label('Nomor Registrasi')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('Contoh: REG-2024-001'),
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
                                    ->required()
                                    ->native(false)
                                    ->columnSpanFull(),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_surat')
                                    ->label('Tanggal Surat')
                                    ->required()
                                     ->default(now())
                                    ->maxDate(now())
                                    ->native(false)
                                    ->suffixIcon('heroicon-m-calendar'),
                                Forms\Components\DatePicker::make('tanggal_diterima')
                                    ->label('Tanggal Diterima')
                                    ->required()
                                    ->default(now())
                                    ->native(false)
                                    ->suffixIcon('heroicon-m-calendar'),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Surat')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('pengirim')
                                    ->label('Pengirim')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nama Instansi / Pengirim'),
                                Forms\Components\Select::make('tujuan_user_id')
                                    ->label('Tujuan')
                                    ->relationship('tujuanUser', 'name')
                                    ->searchable()
                                    ->placeholder('Pilih penerima surat'),
                                Forms\Components\TextInput::make('perihal')
                                    ->label('Perihal')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Pokok isi surat'),
                            ]),
                        Forms\Components\Select::make('sifat')
                            ->label('Sifat Surat')
                            ->options([
                                'Biasa' => 'Biasa',
                                'Segera' => 'Segera',
                                'Sangat Segera' => 'Sangat Segera',
                                'Rahasia' => 'Rahasia',
                            ])
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('status_surat_id')
                            ->default(fn () => \App\Models\StatusSurat::where('nama', 'Sent')->first()?->id ?? \App\Models\StatusSurat::where('is_default', true)->first()?->id),
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
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(102400) // 100MB
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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['creator', 'updater', 'klasifikasiArsip', 'statusSurat', 'tujuanUser']))
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
                    ->label('No. Registrasi')
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
                Tables\Columns\TextColumn::make('tujuanUser.name')
                    ->label('Tujuan')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
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
                Tables\Columns\TextColumn::make('status_context')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $user = auth()->user();
                        // Admin/Super Admin sees the actual document status
                        if ($user->hasAnyRole(['super_admin', 'admin'])) {
                            return $record->statusSurat?->nama;
                        }

                        // Check if user is a disposition recipient
                        $disposisi = $record->disposisis()->where('kepada_user_id', $user->id)->latest()->first();
                        if ($disposisi) {
                            return $disposisi->status; // Pending, Diterima, Selesai
                        }

                        // Check if user is the main recipient
                        if ($record->tujuan_user_id === $user->id) {
                            return $record->status; // PENDING (default now), Diterima, Selesai
                        }

                        // Fallback to document status (e.g., creator or observer)
                        return $record->statusSurat?->nama;
                    })
                    ->colors([
                        'gray' => ['Draft', 'Pending'],
                        'warning' => ['Review', 'Didisposisi', 'Diproses'],
                        'success' => ['Signed', 'Diterima', 'Selesai'],
                        'danger' => ['Sent', 'Archived'],
                        'primary' => 'Biasa',
                    ]),
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
            ->defaultSort('created_at', 'desc')
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
                    Tables\Actions\ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\MaxWidth::FiveExtraLarge),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('terima')
                        ->label('Terima')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => 
                            // Admin/Super Admin can accept Draft
                            ($record->statusSurat?->nama === 'Draft' && auth()->user()->hasAnyRole(['admin', 'super_admin'])) ||
                            // Recipient (Tujuan) can accept if Pending - Including Direktur, Kabag, Kasubag, Staff
                            ($record->tujuan_user_id === auth()->id() && $record->status === 'Pending' && auth()->user()->hasAnyRole(['direktur', 'kepala_bagian', 'kepala_sub_bagian', 'staff'])) ||
                            // Disposition recipient can accept - Including various roles
                            ($record->disposisis()->where('kepada_user_id', auth()->id())->where('status', 'Pending')->exists() && auth()->user()->hasAnyRole(['direktur', 'kepala_bagian', 'kepala_sub_bagian', 'staff']))
                        )
                        ->action(function (SuratMasuk $record) {
                            $user = auth()->user();
                            $isStaff = $user->hasRole('staff');
                            $statusToSet = $isStaff ? 'Selesai' : 'Diterima';

                            // Update surat status if it was draft
                            if ($record->statusSurat?->nama === 'Draft') {
                                $statusDefault = \App\Models\StatusSurat::where('nama', '!=', 'Draft')->where('is_default', true)->first();
                                if ($statusDefault) {
                                    $record->update(['status_surat_id' => $statusDefault->id]);
                                }
                            }
                            
                            // Update Disposisi
                            $disposisiRef = $record->disposisis()->where('kepada_user_id', $user->id)->where('status', 'Pending');
                            $updateData = ['status' => $statusToSet, 'dibaca_pada' => now()];
                            if ($isStaff) {
                                $updateData['selesai_pada'] = now();
                            }
                            $disposisiRef->update($updateData);

                            // Update Main Record if Recipient
                            if ($record->tujuan_user_id === $user->id) {
                                $record->update(['status' => $statusToSet]);
                            }
                            
                            $msg = $isStaff ? 'Surat Berhasil Diterima dan Diselesaikan' : 'Surat Berhasil Diterima';
                            \Filament\Notifications\Notification::make()->title($msg)->success()->send();
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
                        ->color('primary')
                        ->visible(function ($record) {
                             $user = auth()->user();
                             if (!$user->hasAnyRole(['super_admin', 'admin', 'sekretaris', 'direktur', 'kepala_bagian', 'kepala_sub_bagian'])) return false;
                             
                             // Admin/Sekretaris can always dispose (unless restricted, but usually they distribute)
                             if ($user->hasAnyRole(['super_admin', 'admin', 'sekretaris']) && $record->disposisis()->count() == 0) return true;

                             // If acts as Main Recipient
                             if ($record->tujuan_user_id === $user->id) {
                                 return $record->status === 'Diterima';
                             }
                             
                             // If acts as Disposition Recipient
                             $usersDisposition = $record->disposisis()->where('kepada_user_id', $user->id)->latest()->first();
                             if ($usersDisposition) {
                                 // Only visible if status is Diterima
                                 return $usersDisposition->status === 'Diterima';
                             }

                             // Admins can override/dispose anytime? Let's restrict to keep flow clean, or allow if necessary.
                             // For now, return false for anyone else who doesn't 'hold' the letter.
                             if ($user->hasAnyRole(['super_admin', 'admin'])) return true;

                             return false;
                        })
                        ->form([
                            Forms\Components\Select::make('kepada_user_ids')
                                ->label('Tujuan Disposisi')
                                ->options(fn () => \App\Models\User::pluck('name', 'id'))
                                ->multiple()
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
                            // Create multiple dispositions for each recipient
                            foreach ($data['kepada_user_ids'] as $kepadaUserId) {
                                $record->disposisis()->create([
                                    'dari_user_id' => auth()->id(),
                                    'kepada_user_id' => $kepadaUserId,
                                    'instruksi' => $data['instruksi'],
                                    'prioritas' => $data['prioritas'],
                                    'batas_waktu' => $data['batas_waktu'],
                                    'status' => 'Pending',
                                ]);
                            }
                            
                            // Mark current user's disposition as Selesai
                            $record->disposisis()->where('kepada_user_id', auth()->id())->where('status', 'Diterima')
                                ->update(['status' => 'Selesai', 'selesai_pada' => now()]);

                            // If direktur creates disposition, mark as Signed
                            if (auth()->user()->hasRole('direktur')) {
                                $statusSigned = \App\Models\StatusSurat::where('nama', 'Signed')->first();
                                if ($statusSigned) {
                                    $record->update(['status_surat_id' => $statusSigned->id, 'status' => 'Selesai']);
                                }
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Disposisi Berhasil Dibuat')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('bagikan_disposisi')
                        ->label('Bagikan')
                        ->icon('heroicon-o-share')
                        ->color('info')
                        ->visible(fn ($record) => auth()->user()->hasRole('sekretaris') && $record->disposisis()->exists())
                        ->form([
                            Forms\Components\Select::make('kepada_user_ids')
                                ->label('Bagikan Ke')
                                ->options(fn () => \App\Models\User::pluck('name', 'id'))
                                ->multiple()
                                ->searchable()
                                ->required(),
                            Forms\Components\Textarea::make('instruksi')
                                ->label('Instruksi Tambahan')
                                ->required(),
                        ])
                        ->action(function (SuratMasuk $record, array $data) {
                            foreach ($data['kepada_user_ids'] as $kepadaUserId) {
                                $record->disposisis()->create([
                                    'dari_user_id' => auth()->id(),
                                    'kepada_user_id' => $kepadaUserId,
                                    'instruksi' => $data['instruksi'],
                                    'prioritas' => 'Biasa',
                                    'status' => 'Pending',
                                ]);
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Disposisi Berhasil Dibagikan')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('selesaikan')
                        ->label('Selesaikan')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => auth()->user()->hasRole('sekretaris') && $record->disposisis()->exists() && $record->status !== 'Selesai')
                        ->requiresConfirmation()
                        ->action(function (SuratMasuk $record) {
                            $record->update(['status' => 'Selesai']);
                            \Filament\Notifications\Notification::make()
                                ->title('Surat Telah Diselesaikan')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('cetak_disposisi')
                        ->label('Cetak Disposisi')
                        ->icon('heroicon-o-printer')
                        ->color('secondary')
                        ->visible(fn ($record) => $record->disposisis()->exists())
                        ->action(function (SuratMasuk $record) {
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.disposisi-all', [
                                'suratMasuk' => $record->load('disposisis.dariUser', 'disposisis.kepadaUser')
                            ]);
                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'disposisi-' . str_replace(['/', '\\'], '-', $record->nomor_agenda) . '.pdf');
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
                        \Filament\Infolists\Components\TextEntry::make('nomor_agenda')
                            ->label('Nomor Registrasi'),
                        \Filament\Infolists\Components\TextEntry::make('pengirim'),
                        \Filament\Infolists\Components\TextEntry::make('tujuanUser.name')
                            ->label('Tujuan'),
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

                \Filament\Infolists\Components\Section::make('Lampiran File')
                    ->schema([
                        \Filament\Infolists\Components\ViewEntry::make('file_path')
                            ->view('filament.infolists.pdf-viewer')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record->file_path && str_ends_with(strtolower($record->file_path), '.pdf')),

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
