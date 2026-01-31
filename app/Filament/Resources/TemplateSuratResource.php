<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateSuratResource\Pages;
use App\Models\TemplateSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TemplateSuratResource extends Resource
{
    protected static ?string $model = TemplateSurat::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Template Surat';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Header & Branding')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('logo_surat')
                                    ->label('Logo Template (Specific)')
                                    ->directory('template-logos')
                                    ->image(),
                                Forms\Components\RichEditor::make('kop_surat')
                                    ->label('Kop Surat Custom')
                                    ->helperText('Biarkan kosong untuk menggunakan kop default instansi.')
                                    ->columnSpan(1),
                            ]),
                    ]),
                Forms\Components\Section::make('Template Surat')
                    ->description('Kelola template untuk surat otomatis. Gunakan placeholder seperti {{nomor_surat}}, {{tujuan}}, {{perihal}}, {{isi_surat}}')
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Template')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Tabs::make('Editor')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Konten HTML')
                                    ->schema([
                                        Forms\Components\RichEditor::make('content')
                                            ->label('Isi Template')
                                            ->required()
                                            ->reactive()
                                            ->debounce(1000)
                                            ->columnSpanFull()
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline', 'strike',
                                                'bulletList', 'orderedList', 'h2', 'h3',
                                                'codeBlock', 'blockquote', 'undo', 'redo',
                                            ]),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Live Preview')
                                    ->schema([
                                    Forms\Components\Placeholder::make('preview')
                                        ->label('Preview')
                                        ->content(fn (Forms\Get $get) => view('filament.forms.components.template-preview', [
                                            'get' => $get,
                                            'getState' => fn () => $get('content'),
                                        ]))
                                        ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Template')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplateSurats::route('/'),
            'create' => Pages\CreateTemplateSurat::route('/create'),
            'edit' => Pages\EditTemplateSurat::route('/{record}/edit'),
        ];
    }
}
