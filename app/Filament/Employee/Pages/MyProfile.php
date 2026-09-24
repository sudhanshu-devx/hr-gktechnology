<?php

namespace App\Filament\Employee\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class MyProfile extends Page
{
    // ✅ MUST be NON-static (matches parent)
    protected string $view = 'filament.employee.pages.my-profile';

    // ✅ MUST be ?string (matches parent)
    protected static ?string $navigationLabel = 'My Profile';

    // ✅ This one CAN be enum|string|null
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::User;

    public $user;

    public function mount(): void
    {
        $this->user = Auth::user()
            ->load(['kyc', 'bank', 'department', 'position']);
    }

    public static function canAccess(): bool
    {
        return Auth::check();
    }
}
