<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisposisiResource\Pages;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;

class DisposisiResource extends Resource
{
    protected static ?string $model = Disposisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    
    protected static ?string $navigationLabel = 'Disposisi';
    
    protected static ?string $navigationGroup = 'Persuratan';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->description('Surat yang akan didisposisikan')
                    ->schema([
                        Forms\Components\Select::make('surat_masuk_id')
                            ->label('Surat Masuk')
                            ->options(SuratMasuk::query()
                                ->select(['id', 'nomor_surat', 'perihal'])
                                ->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->id => $item->nomor_surat . ' - ' . $item->perihal
                                ])
                            )
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Disposisi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('dari_user_id')
                                    ->label('Dari')
                                    ->options(User::query()->pluck('name', 'id'))
                                    ->required()
                                    ->default(Auth::id())
                                    ->disabled()
                                    ->dehydrated()
                                    ->searchable()
                                    ->native(false),
                                Forms\Components\Select::make('kepada_user_id')
                                    ->label('Kepada')
                                    ->options(User::query()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->native(false),
                            ]),
                        Forms\Components\RichEditor::make('instruksi')
                            ->label('Instruksi Disposisi')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('prioritas')
                                    ->label('Prioritas')
                                    ->options([
                                        'Biasa' => 'Biasa',
                                        'Segera' => 'Segera',
                                        'Sangat Segera' => 'Sangat Segera',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->default('Biasa'),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Pending' => 'Pending',
                                        'Dibaca' => 'Dibaca',
                                        'Diproses' => 'Diproses',
                                        'Selesai' => 'Selesai',
                                    ])
                                    ->default('Pending')
                                    ->required()
                                    ->native(false),
                                Forms\Components\DatePicker::make('batas_waktu')
                                    ->label('Batas Waktu')
                                    ->native(false),
                            ]),
                    ]),

                Forms\Components\Section::make('Penyelesaian')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('dibaca_pada')
                                    ->label('Dibaca Pada')
                                    ->native(false)
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\DateTimePicker::make('selesai_pada')
                                    ->label('Selesai Pada')
                                    ->native(false)
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                        Forms\Components\Textarea::make('catatan_penyelesaian')
                            ->label('Catatan Penyelesaian')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['suratMasuk', 'dariUser', 'kepadaUser', 'creator']))
            ->columns([
                Tables\Columns\TextColumn::make('suratMasuk.nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('suratMasuk.perihal')
                    ->label('Perihal')
                    ->searchable()
                    ->limit(30)
                    ->wrap(),
                Tables\Columns\TextColumn::make('dariUser.name')
                    ->label('Dari')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kepadaUser.name')
                    ->label('Kepada')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('prioritas')
                    ->label('Prioritas')
                    ->colors([
                        'success' => 'Biasa',
                        'warning' => 'Segera',
                        'danger' => 'Sangat Segera',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'Pending',
                        'info' => 'Dibaca',
                        'warning' => 'Diproses',
                        'success' => 'Selesai',
                    ]),
                Tables\Columns\TextColumn::make('batas_waktu')
                    ->label('Batas Waktu')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('catatan_pengembalian')
                    ->label('Alasan Kembali')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->catatan_pengembalian)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('dibaca_pada')
                    ->label('Dibaca')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('selesai_pada')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Dibaca' => 'Dibaca',
                        'Diproses' => 'Diproses',
                        'Selesai' => 'Selesai',
                    ]),
                Tables\Filters\SelectFilter::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'Biasa' => 'Biasa',
                        'Segera' => 'Segera',
                        'Sangat Segera' => 'Sangat Segera',
                    ]),
                Tables\Filters\SelectFilter::make('kepada_user_id')
                    ->label('Kepada')
                    ->relationship('kepadaUser', 'name'),
                Tables\Filters\SelectFilter::make('dari_user_id')
                    ->label('Dari')
                    ->relationship('dariUser', 'name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('kembalikan')
                        ->label('Kembalikan')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->form([
                            Forms\Components\Textarea::make('catatan_pengembalian')
                                ->label('Alasan Pengembalian')
                                ->required(),
                        ])
                        ->visible(fn ($record) => $record->kepada_user_id === auth()->id() && $record->status !== 'Selesai')
                        ->action(function (Disposisi $record, array $data) {
                            $record->update([
                                'status' => 'Pending', // Or add a 'Dikembalikan' status
                                'catatan_pengembalian' => $data['catatan_pengembalian'],
                            ]);
                            
                            // Notify the sender
                            \Filament\Notifications\Notification::make()
                                ->title('Disposisi Dikembalikan')
                                ->body('Alasan: ' . $data['catatan_pengembalian'])
                                ->warning()
                                ->sendToDatabase($record->dariUser);
                        }),

                    Tables\Actions\Action::make('download')
                        ->label('Cetak Disposisi')
                        ->icon('heroicon-o-printer')
                        ->action(fn (Disposisi $record) => \App\Services\PdfService::printDisposisi($record)),

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
            ->defaultSort('created_at', 'desc');
    }

    public static function getWidgets(): array
    {
        return [
            DisposisiResource\Widgets\DisposisiStats::class,
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
            'index' => Pages\ListDisposisis::route('/'),
            'create' => Pages\CreateDisposisi::route('/create'),
            'view' => Pages\ViewDisposisi::route('/{record}'),
            'edit' => Pages\EditDisposisi::route('/{record}/edit'),
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
