<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotulensiResource\Pages;
use App\Filament\Resources\NotulensiResource\RelationManagers;
use App\Models\Notulensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotulensiResource extends Resource
{
    protected static ?string $model = Notulensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Notulensi';
    protected static ?string $navigationGroup = 'Agenda & Arsip';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Pending Approval')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Rapat')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal')
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('tempat')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Ruang Rapat Lt. 2'),
                                Forms\Components\TextInput::make('agenda')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Rapat Koordinasi Bulanan')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('pimpinan_rapat')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nama pimpinan rapat'),
                                Forms\Components\Select::make('notulis_id')
                                    ->relationship('notulis', 'name')
                                    ->default(auth()->id())
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->disabled(!auth()->user()->hasRole('super_admin'))
                                    ->dehydrated(),
                            ]),
                    ]),

                Forms\Components\Section::make('Daftar Peserta')
                    ->schema([
                        Forms\Components\Repeater::make('peserta')
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->required(),
                                Forms\Components\TextInput::make('jabatan'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Peserta'),
                    ]),

                Forms\Components\Section::make('Isi Notulensi')
                    ->schema([
                        Forms\Components\RichEditor::make('isi_notulensi')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Lampiran (Dokumen/Foto)')
                            ->directory('notulensi')
                            ->saveUploadedFileUsing(function (\Illuminate\Http\UploadedFile $file) {
                                return \App\Services\FileEncryptionService::encryptAndStore($file, 'notulensi');
                            })
                            ->downloadable()
                            ->openable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('agenda')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('pimpinan_rapat')
                    ->label('Pimpinan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'gray',
                        'Pending Approval' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Forwarded' => 'info',
                    }),
                Tables\Columns\TextColumn::make('notulis.name')
                    ->label('Notulis')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Pending Approval' => 'Pending Approval',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                        'Forwarded' => 'Forwarded',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('request_approval')
                        ->label('Ajukan Persetujuan')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('warning')
                        ->visible(fn (Notulensi $record) => $record->status === 'Draft')
                        ->action(fn (Notulensi $record) => $record->update(['status' => 'Pending Approval'])),

                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Notulensi $record) => $record->status === 'Pending Approval' && auth()->user()->hasRole(['Super Admin', 'User (Dirut)']))
                        ->action(fn (Notulensi $record) => $record->update([
                            'status' => 'Approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ])),

                    Tables\Actions\Action::make('forward')
                        ->label('Teruskan ke User')
                        ->icon('heroicon-o-share')
                        ->color('info')
                        ->form([
                            Forms\Components\Select::make('users')
                                ->label('Pilih User')
                                ->multiple()
                                ->options(\App\Models\User::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->visible(fn (Notulensi $record) => $record->status === 'Approved')
                        ->action(function (Notulensi $record, array $data) {
                            // Forward logic: send notifications to selected users
                            foreach ($data['users'] as $userId) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Notulensi Rapat Baru: ' . $record->agenda)
                                    ->body('Anda menerima terusan notulensi rapat tanggal ' . $record->tanggal->format('d M Y'))
                                    ->info()
                                    ->actions([
                                        \Filament\Notifications\Actions\Action::make('Lihat')
                                            ->url(NotulensiResource::getUrl('view', ['record' => $record])),
                                    ])
                                    ->sendToDatabase(\App\Models\User::find($userId));
                            }

                            $record->update(['status' => 'Forwarded']);

                            \Filament\Notifications\Notification::make()
                                ->title('Berhasil diteruskan')
                                ->success()
                                ->send();
                        }),

                     Tables\Actions\Action::make('download_pdf')
                        ->label('Unduh PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->action(function (Notulensi $record) {
                            return \App\Services\PdfService::printNotulensi($record);
                        }),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            NotulensiResource\Widgets\NotulensiStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNotulensis::route('/'),
        ];
    }
}
