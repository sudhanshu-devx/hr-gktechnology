<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->renderHook(
    \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
    fn () => view('auth.panel-switch')
)

            ->maxContentWidth('full')

            // 🔐 IMPORTANT
            ->authGuard('web')

            // 🎨 Branding
            ->brandLogo(asset('/public/images/logo.svg'))
            ->brandLogoHeight('6rem')
            ->favicon(asset('/public/images/logo.svg'))
            ->colors([
                'primary' => Color::Indigo,
            ])

            // 📦 Resources / Pages / Widgets
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )
            ->widgets([
                AccountWidget::class,
                
            ])

            // 🧱 MIDDLEWARE (DO NOT ADD VerifyCsrfToken)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            // 🔐 AUTH MIDDLEWARE
            ->authMiddleware([
                Authenticate::class,
            ])

            // 🛡️ Shield
            ->plugins([
                FilamentShieldPlugin::make(),
            ]);
    }
}