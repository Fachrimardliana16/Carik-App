<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserDelegationResource\Pages;
use App\Models\UserDelegation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserDelegationResource extends Resource
{
    protected static ?string $model = UserDelegation::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Delegasi (Plh)';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Delegasi Wewenang (Plh)')
                    ->description('Pengaturan pelaksana harian saat pejabat berhalangan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Pejabat (Pemberi Kuasa)')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('delegate_user_id')
                                    ->label('Pelaksana Harian (Penerima)')
                                    ->relationship('delegateUser', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->different('user_id'),
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->required()
                                    ->native(false),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Tanggal Berakhir')
                                    ->required()
                                    ->native(false),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Textarea::make('notes')
                            ->label('Keterangan / Dasar Surat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pejabat')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('delegateUser.name')
                    ->label('Pelaksana Harian (Plh)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserDelegations::route('/'),
            'create' => Pages\CreateUserDelegation::route('/create'),
            'edit' => Pages\EditUserDelegation::route('/{record}/edit'),
        ];
    }
}
