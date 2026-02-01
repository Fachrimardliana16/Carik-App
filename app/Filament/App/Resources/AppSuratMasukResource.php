<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\SuratMasukResource;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppSuratMasukResource extends SuratMasukResource
{
    protected static ?string $modelLabel = 'Surat Masuk';
    protected static ?string $pluralModelLabel = 'Surat Masuk';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($user) {
                // Show letters where:
                // 1. Created by this user, OR
                // 2. Addressed to this user (tujuan), OR
                // 3. Has a disposition to this user
                
                $query->where('created_by', $user->id)
                      ->orWhere('tujuan_user_id', $user->id)
                      ->orWhereHas('disposisis', function ($q) use ($user) {
                          $q->where('kepada_user_id', $user->id);
                      });
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\AppSuratMasukResource\Pages\ListAppSuratMasuks::route('/'),
            'create' => \App\Filament\App\Resources\AppSuratMasukResource\Pages\CreateAppSuratMasuk::route('/create'),
            'view' => \App\Filament\App\Resources\AppSuratMasukResource\Pages\ViewAppSuratMasuk::route('/{record}'),
        ];
    }
}
