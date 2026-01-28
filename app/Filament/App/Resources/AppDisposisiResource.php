<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\DisposisiResource;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppDisposisiResource extends DisposisiResource
{
    protected static ?string $modelLabel = 'Disposisi';
    protected static ?string $pluralModelLabel = 'Disposisi';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($user) {
                $query->where('dari_user_id', $user->id)
                      ->orWhere('kepada_user_id', $user->id);
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\AppDisposisiResource\Pages\ListAppDisposisis::route('/'),
            'create' => \App\Filament\App\Resources\AppDisposisiResource\Pages\CreateAppDisposisi::route('/create'),
            'view' => \App\Filament\App\Resources\AppDisposisiResource\Pages\ViewAppDisposisi::route('/{record}'),
            'edit' => \App\Filament\App\Resources\AppDisposisiResource\Pages\EditAppDisposisi::route('/{record}/edit'),
        ];
    }
}
