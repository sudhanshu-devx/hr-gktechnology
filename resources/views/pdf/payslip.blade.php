<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            background: #fff;
            margin: 0;
            padding: 20px 24px;
        }

        .header {
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .logo {
            text-align: center;
            margin-bottom: 6px;
        }

        .logo img {
            height: 90px;
        }

        .company-name {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .company-address {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }

        .payslip-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 16px 0 10px;
            color: #d32f2f;
        }

        .meta-box {
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 7px 8px;
            font-size: 11.5px;
        }

        .label {
            color: #666;
            width: 25%;
        }

        .value {
            font-weight: bold;
            color: #000;
        }

        .section-title {
            margin-top: 14px;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: bold;
            color: #d32f2f;
        }

        .salary-table {
            border: 1px solid #eee;
            border-radius: 6px;
            overflow: hidden;
        }

        .salary-table th {
            background: #f8f8f8;
            color: #444;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .salary-table td {
            border-bottom: 1px solid #eee;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .total-row {
            background: #fafafa;
            font-weight: bold;
        }

        .net-pay-box {
            border: 2px dashed #d32f2f;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 14px;
        }

        .net-pay-label {
            font-size: 12px;
            color: #666;
        }

        .net-pay-value {
            font-size: 18px;
            font-weight: bold;
            color: #d32f2f;
        }

        .amount-words {
            margin-top: 8px;
            font-size: 11.5px;
            font-style: italic;
        }

        .footer {
            margin-top: 18px;
            font-size: 10.5px;
            color: #666;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo">
        <img src="file:///home/u775048470/domains/hr-espiritsindia.com/public_html/images/logo.png">
    </div>

    <div class="company-name">E-SPIRITS IT SERVICES LLP</div>
    <div class="company-address">
        <div>2/11, ADA Flat, Tulsipur, Prayagraj - 211003</div>
    <div>Sikka House Plot No A 1/1 Vibhuti Khand, Gomti Nagar, Lucknow, UP - 226010</div>
</div>
    </div>
</div>

<div class="payslip-title">
    Payslip — 
    {{ \Carbon\Carbon::createFromDate(null, (int) $payroll->month, 1)->format('F') }}
    {{ $payroll->year }}
</div>

{{-- Employee Info --}}
<div class="meta-box">
    <table>
        <tr>
            <td class="label">Employee Name</td>
            <td class="value">{{ $payroll->user->name }}</td>

            <td class="label">Paid Days</td>
            <td class="value">30</td>
        </tr>

        <tr>
            <td class="label">Designation</td>
            <td class="value">{{ $payroll->user->position->title ?? '—' }}</td>

            <td class="label">PAN Number</td>
            <td class="value">{{ optional($payroll->user->kyc)->pan_number ?? '—' }}</td>
        </tr>

        <tr>
            <td class="label">Bank Name</td>
            <td class="value">{{ optional($payroll->user->bank)->bank_name ?? '—' }}</td>

            <td class="label">Account No.</td>
            <td class="value">{{ optional($payroll->user->bank)->account_number ?? '—' }}</td>
        </tr>

        <tr>
            <td class="label">IFSC Code</td>
            <td class="value">{{ optional($payroll->user->bank)->ifsc_code ?? '—' }}</td>

            <td></td>
            <td></td>
        </tr>
    </table>
</div>

{{-- Salary Breakdown --}}
<h3 style="color:#e53935; margin-top:20px;">Salary Breakdown</h3>

<table style="width:100%; border-collapse:collapse; font-size:12px;">
    <thead>
        <tr style="background:#f7f7f7;">
            <th style="border:1px solid #ddd; padding:8px;">Earnings</th>
            <th style="border:1px solid #ddd; padding:8px;" class="right">Amount (₹)</th>
            <th style="border:1px solid #ddd; padding:8px;">Deductions</th>
            <th style="border:1px solid #ddd; padding:8px;" class="right">Amount (₹)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #eee; padding:8px;">Basic Salary</td>
            <td style="border:1px solid #eee; padding:8px;" class="right">
                {{ number_format($payroll->basic_salary, 2) }}
            </td>
            <td style="border:1px solid #eee; padding:8px;">Other Deductions</td>
            <td style="border:1px solid #eee; padding:8px;" class="right">
                {{ number_format($payroll->deductions ?? 0, 2) }}
            </td>
        </tr>

        <tr>
            <td style="border:1px solid #eee; padding:8px;">HRA</td>
            <td style="border:1px solid #eee; padding:8px;" class="right">
                {{ number_format($payroll->allowances ?? 0, 2) }}
            </td>
            <td style="border:1px solid #eee; padding:8px;"></td>
            <td style="border:1px solid #eee; padding:8px;"></td>
        </tr>

        <tr>
            <td style="border:1px solid #eee; padding:8px;">Special Allowance</td>
            <td style="border:1px solid #eee; padding:8px;" class="right">
                {{ number_format($payroll->bonus ?? 0, 2) }}
            </td>
            <td style="border:1px solid #eee; padding:8px;"></td>
            <td style="border:1px solid #eee; padding:8px;"></td>
        </tr>

        <tr style="font-weight:bold; background:#fafafa;">
            <td style="border:1px solid #ddd; padding:8px;">Total Earnings</td>
            <td style="border:1px solid #ddd; padding:8px;" class="right">
                {{
                    number_format(
                        $payroll->basic_salary +
                        ($payroll->allowances ?? 0) +
                        ($payroll->bonus ?? 0),
                        2
                    )
                }}
            </td>
            <td style="border:1px solid #ddd; padding:8px;">Total Deductions</td>
            <td style="border:1px solid #ddd; padding:8px;" class="right">
                {{ number_format($payroll->deductions ?? 0, 2) }}
            </td>
        </tr>
    </tbody>
</table>


<br>

{{-- Net Salary --}}
<table style="border:2px dashed #e11d2e;">
    <tr class="bold">
        <td>Net Salary Payable</td>
        <td class="right" style="color:#e11d2e; font-size:16px;">
            ₹ {{ number_format($payroll->net_salary, 2) }}
        </td>
    </tr>
</table>

<p style="margin-top:8px;">
    <strong>Amount in words:</strong>
    {{
        \Illuminate\Support\Str::ucfirst(
            \NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)
                ->format($payroll->net_salary)
        )
    }} only
</p>


<div class="footer">
    This is a system-generated payslip and does not require a signature.
</div>

</body>
</html>
