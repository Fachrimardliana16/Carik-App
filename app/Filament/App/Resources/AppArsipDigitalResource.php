<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\ArsipDigitalResource;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppArsipDigitalResource extends ArsipDigitalResource
{
    protected static ?string $modelLabel = 'Arsip Saya';
    protected static ?string $navigationGroup = 'Menu Saya';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('uploaded_by', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\AppArsipDigitalResource\Pages\ListAppArsipDigitals::route('/'),
            'create' => \App\Filament\App\Resources\AppArsipDigitalResource\Pages\CreateAppArsipDigital::route('/create'),
            'edit' => \App\Filament\App\Resources\AppArsipDigitalResource\Pages\EditAppArsipDigital::route('/{record}/edit'),
        ];
    }
}
