<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\ReviewSuratResource;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppReviewSuratResource extends ReviewSuratResource
{
    protected static ?string $modelLabel = 'Review Surat';
    protected static ?string $navigationGroup = 'Menu Saya';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        // View reviews assigned to me OR created by me (if reviewer is logic differently)
        // Table Structure: review_surats (surat_keluar_id, reviewer_id, etc)
        // Usually "Review Surat" means "I need to review this".
        return parent::getEloquentQuery()
            ->where('reviewer_id', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\AppReviewSuratResource\Pages\ListAppReviewSurats::route('/'),
            'edit' => \App\Filament\App\Resources\AppReviewSuratResource\Pages\EditAppReviewSurat::route('/{record}/edit'),
        ];
    }
}
