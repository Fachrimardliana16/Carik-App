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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pejabat (Pemberi Kuasa)')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('delegate_user_id')
                    ->label('Pelaksana Harian (Penerima)')
                    ->relationship('delegateUser', 'name')
                    ->required()
                    ->searchable()
                    ->different('user_id'),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')->required(),
                        Forms\Components\DatePicker::make('end_date')->required(),
                    ]),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
                Forms\Components\Textarea::make('notes')
                    ->label('Keterangan / Dasar Surat')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Pejabat'),
                Tables\Columns\TextColumn::make('delegateUser.name')->label('Plh'),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
