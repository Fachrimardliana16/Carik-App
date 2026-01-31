<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManualBookResource\Pages;
use App\Filament\Resources\ManualBookResource\RelationManagers;
use App\Models\ManualBook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ManualBookResource extends Resource
{
    protected static ?string $model = ManualBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Manual')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ManualBook::class, 'slug', ignoreRecord: true),
                        
                        Forms\Components\RichEditor::make('content')
                            ->columnSpanFull()
                            ->required()
                            ->fileAttachmentsDirectory('manual-books'),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('order')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('#'),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->required()
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->label('Urutan'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Aktif'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListManualBooks::route('/'),
            'create' => Pages\CreateManualBook::route('/create'),
            'edit' => Pages\EditManualBook::route('/{record}/edit'),
        ];
    }
}
