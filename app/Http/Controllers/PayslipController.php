<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PayslipController extends Controller
{
    
    public function download(Payroll $payroll)
{
    $this->authorizeAccess($payroll);

    $payroll->load([
        'user',
        'user.position',
        'user.kyc',
        'user.bank',
    ]);

    // 🔥 Auto decide format based on location
    if (strtolower($payroll->user->location) === 'prayagraj') {
        return $this->generatePdf(
            $payroll,
            'pdf.payslip-gk',
            'images/gklogo.png',
            'GK'
        );
    }

    // Default → Lucknow → Espirits
    return $this->generatePdf(
        $payroll,
        'pdf.payslip',
        'images/logo.png',
        'Espirits'
    );
}
    /*
    |--------------------------------------------------------------------------
    | ESPIRITS DOWNLOAD
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | PERMISSION CHECK
    |--------------------------------------------------------------------------
    */
    protected function authorizeAccess(Payroll $payroll)
    {
        $user = Auth::user();

        // HR / Admin permission
        if ($user->can('View:Payroll')) {
            return true;
        }

        // Employee can download ONLY their own payslip
        if ($user->id === $payroll->user_id) {
            return true;
        }

        abort(403, 'Unauthorized access to payslip.');
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE PDF (Reusable)
    |--------------------------------------------------------------------------
    */
    protected function generatePdf(
        Payroll $payroll,
        string $view,
        string $logoFile,
        string $companyPrefix
    ) {
        // Eager load relations
        $payroll->load([
            'user',
            'user.position',
            'user.kyc',
            'user.bank',
        ]);

        // Base64 Logo (Hostinger safe)
         $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $logoFile;
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(
                file_get_contents($logoPath)
            );
        }

        $pdf = Pdf::loadView($view, [
            'payroll'    => $payroll,
            'logoBase64' => $logoBase64,
        ])->setPaper('A4', 'portrait');

        return $pdf->download(
            $companyPrefix . '-Payslip-' .
            $payroll->user->employee_id . '-' .
            $payroll->month . '-' .
            $payroll->year . '.pdf'
        );
    }
}