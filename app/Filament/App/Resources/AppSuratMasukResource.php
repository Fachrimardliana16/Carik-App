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
                // Show letters where creation is by user, OR user is involved (e.g. through dispositions - simpler logic for now: all visible or limited?)
                // "Filter datanya saja per user"
                // Assuming "Surat Masuk" for a Unit user means letters *dispositioned* to them, OR created by them (if they can create).
                // But SuratMasuk is typically "General Box". 
                // Let's filter by: Has a disposition to this user.
                
                $query->where('created_by', $user->id)
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
