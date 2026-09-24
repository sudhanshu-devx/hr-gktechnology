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
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Hr\Pages\GenerateOfferLetter;




class HrPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('hr')
            ->path('hr')
            ->login()
            ->renderHook(
    \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
    fn () => view('auth.panel-switch')
)

            ->passwordReset()
            ->brandName('G.K. Technology')
            ->brandLogo(asset('images/logo.svg'))
            ->brandLogoHeight('6rem')
            ->favicon(asset('images/logo.svg'))
            ->colors([
                'primary' => Color::Amber,
            ])
     ->resources([
    UserResource::class,
])

            ->discoverResources(in: app_path('Filament/Hr/Resources'), for: 'App\Filament\Hr\Resources')
            ->discoverPages(in: app_path('Filament/Hr/Pages'), for: 'App\Filament\Hr\Pages')
            ->pages([
                Dashboard::class,
                
            ])
            ->discoverWidgets(in: app_path('Filament/Hr/Widgets'), for: 'App\Filament\Hr\Widgets')
            ->widgets([
                AccountWidget::class,
                
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
            ->authMiddleware([
                Authenticate::class,
            ])->plugins([
                FilamentShieldPlugin::make()
            ]);
    }
}
