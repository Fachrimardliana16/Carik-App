<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\SuratKeluarResource;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppSuratKeluarResource extends SuratKeluarResource
{
    protected static ?string $modelLabel = 'Surat Keluar';
    protected static ?string $pluralModelLabel = 'Surat Keluar';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        
        // Get user IDs who delegated to me
        $delegatedIds = \App\Models\UserDelegation::where('delegate_user_id', $user->id)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->pluck('user_id')
            ->toArray();
            
        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($user, $delegatedIds) {
                // Show surat created by user
                $query->where('created_by', $user->id)
                      // Or where user is the signatory
                      ->orWhere('penandatangan_id', $user->id)
                      // Or where user is delegated as signatory
                      ->orWhereIn('penandatangan_id', $delegatedIds)
                      // Or internal surat where user is the recipient
                      ->orWhere(function ($q) use ($user) {
                          $q->where('is_internal', true)
                            ->where('tujuan_user_id', $user->id);
                      })
                      // Or surat with disposisi to this user
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
            'index' => \App\Filament\App\Resources\AppSuratKeluarResource\Pages\ListAppSuratKeluars::route('/'),
            'create' => \App\Filament\App\Resources\AppSuratKeluarResource\Pages\CreateAppSuratKeluar::route('/create'),
            'view' => \App\Filament\App\Resources\AppSuratKeluarResource\Pages\ViewAppSuratKeluar::route('/{record}'),
            'edit' => \App\Filament\App\Resources\AppSuratKeluarResource\Pages\EditAppSuratKeluar::route('/{record}/edit'),
        ];
    }
}
