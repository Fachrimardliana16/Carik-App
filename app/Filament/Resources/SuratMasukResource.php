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
                                Forms\Components\TextInput::make('nomor_surat')
                                    ->label('Nomor Surat')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('Contoh: 005/DK/X/2023')
                                    ->helperText('Nomor yang tertera pada surat fisik.'),
                                Forms\Components\TextInput::make('nomor_agenda')
                                    ->label('Nomor Agenda')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('AGD-YYYYMMDD...')
                                    ->default(fn () => 'AGD-' . date('YmdHis'))
                                    ->helperText('Nomor pencatatan sistem.'),
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
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Diterima' => 'Diterima',
                                        'Didisposisi' => 'Didisposisi',
                                        'Diproses' => 'Diproses',
                                        'Selesai' => 'Selesai',
                                    ])
                                    ->default('Diterima')
                                    ->required()
                                    ->native(false),
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
                            ->downloadable()
                            ->openable()
                            ->previewable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['creator', 'updater']))
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
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
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'Diterima',
                        'warning' => 'Didisposisi',
                        'primary' => 'Diproses',
                        'success' => 'Selesai',
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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Diterima' => 'Diterima',
                        'Didisposisi' => 'Didisposisi',
                        'Diproses' => 'Diproses',
                        'Selesai' => 'Selesai',
                    ]),
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
                ]),
            ])
            ->defaultSort('tanggal_diterima', 'desc');
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
