<?php

namespace App\Providers;

use App\Models\Payroll;
use App\Policies\PayrollPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Payroll::class => PayrollPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
