<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayslipController;

Route::get('/', function () {
    return redirect('/employee');
});

/*
|--------------------------------------------------------------------------
| Payslip PDF Download
|--------------------------------------------------------------------------
| Auth protected
*/
Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/payslip/{payroll}',
        [PayslipController::class, 'download']
    )->name('payslip.download');

});