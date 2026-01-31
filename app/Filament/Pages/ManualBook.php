<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ManualBook extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Manual Book';
    protected static ?string $title = 'Panduan Penggunaan';
    protected static ?string $slug = 'manual-book';

    // Reuse the view from the App panel since it's identical
    protected static string $view = 'filament.app.pages.manual-book';

    public $manualBooks;

    public function mount()
    {
        $this->manualBooks = \App\Models\ManualBook::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
    }
}
