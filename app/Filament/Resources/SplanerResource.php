<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SplanerResource\Pages;
use App\Models\Splaner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\SplanerResource\Widgets\SplanerCalendarWidget;

class SplanerResource extends Resource
{
    protected static ?string $model = Splaner::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Agenda & Arsip';
    protected static ?string $navigationLabel = 'S-Planer (Agenda)';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\DateTimePicker::make('start_time')->required(),
                Forms\Components\DateTimePicker::make('end_time')->required(),
                Forms\Components\TextInput::make('location'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Dijadwalkan' => 'Dijadwalkan',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ])->default('Dijadwalkan')->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->placeholder('Detail kegiatan, link meeting, atau catatan lainnya...'),
                Forms\Components\Hidden::make('user_id')->default(Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('start_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('end_time')->dateTime(),
                Tables\Columns\TextColumn::make('location')->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'Dijadwalkan',
                        'success' => 'Selesai',
                        'danger' => 'Dibatalkan',
                    ]),
            ])
            ->defaultSort('start_time', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSplaners::route('/'),
            'create' => Pages\CreateSplaner::route('/create'),
            'edit' => Pages\EditSplaner::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            SplanerCalendarWidget::class,
        ];
    }
}
