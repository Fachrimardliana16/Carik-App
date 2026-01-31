<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Services\SettingsService;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->brandName(fn () => SettingsService::getCompanyName())
            ->brandLogo(fn () => SettingsService::getLogoLight())
            ->darkModeBrandLogo(fn () => SettingsService::getLogoDark() ?? SettingsService::getLogoLight())
            ->favicon(fn () => SettingsService::getFavicon())
            ->colors([
                'primary' => Color::hex(SettingsService::getPrimaryColor()),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class, // Removed for cleaner look
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable(),
            ])
            ->navigationGroups([
                'Persuratan',
                'Agenda & Arsip',
                'Buku Bantu',
                'Pengaturan',
                'System',
                'Tools',
            ])
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->userMenuItems([
                'panel.app' => \Filament\Navigation\MenuItem::make()
                    ->label('Panel User')
                    ->url('/app')
                    ->icon('heroicon-o-arrow-right-on-rectangle'),
            ]);
    }
}
