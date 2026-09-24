<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayslipController;

/*
|--------------------------------------------------------------------------
| Filament Routes
|--------------------------------------------------------------------------
| These routes are automatically prefixed with:
| filament.{panelId}.
| Example:
| filament.admin.payslip.download
*/

Route::get('/payslip/{payroll}', [PayslipController::class, 'download'])
    ->name('payslip.download');
