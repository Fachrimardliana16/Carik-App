<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\SplanerResource;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppSplanerResource extends SplanerResource
{
    protected static ?string $modelLabel = 'Agenda Saya';
    protected static ?string $navigationGroup = 'Menu Saya';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\AppSplanerResource\Pages\ListAppSplaners::route('/'),
            'create' => \App\Filament\App\Resources\AppSplanerResource\Pages\CreateAppSplaner::route('/create'),
            'edit' => \App\Filament\App\Resources\AppSplanerResource\Pages\EditAppSplaner::route('/{record}/edit'),
        ];
    }
    
    public static function getWidgets(): array
    {
        return [
            \App\Filament\App\Resources\AppSplanerResource\Widgets\AppSplanerCalendarWidget::class,
        ];
    }
}
