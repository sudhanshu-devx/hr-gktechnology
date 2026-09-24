<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<title>GK Offer Letter</title>

<style>
body{
font-family: DejaVu Sans, sans-serif;
font-size:12px;
color:#222;
margin:0;
padding:24px;
}

.header{
border-bottom:3px solid #fbc02d;
padding-bottom:12px;
margin-bottom:20px;
text-align:center;
}

.logo img{
height:90px;
}

.company-name{
font-size:18px;
font-weight:bold;
color:#fbc02d;
margin-top:6px;
}

.company-address{
font-size:11px;
color:#666;
margin-top:3px;
}

.title{
text-align:center;
font-size:16px;
font-weight:bold;
margin:20px 0;
color:#fbc02d;
}

.section-box{
border:1px solid #eee;
border-radius:6px;
padding:12px;
margin-bottom:14px;
}

.highlight{
color:#fbc02d;
font-weight:bold;
}

.signature-table{
width:100%;
margin-top:40px;
}

.signature-table td{
vertical-align:top;
}

.divider{
margin-top:40px;
border-top:3px solid #333;
}

.annexure-title{
text-align:center;
font-weight:bold;
margin-top:30px;
}

.footer{
margin-top:20px;
font-size:11px;
text-align:center;
color:#666;
}
</style>

</head>

<body>

@php
$employeeName = $offer->user?->name ?? $offer->employee_name;

$logoPath = public_path('images/gklogo.png');
$logo = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
@endphp

<div class="header">

<div class="logo">
<img src="file:///home/u775048470/domains/hr-espiritsindia.com/public_html/images/gklogo.png">
</div>

<div class="company-name">
G.K. TECHNOLOGY 
</div>

<div class="company-address">
131N/G-02/02 KAREILA BAGH PRAYAGRAJ-211003
</div>

</div>

<div class="title">
OFFER LETTER
</div>

<p>Date: {{ now()->format('d-m-Y') }}</p>

<p>
Dear <strong>{{ $employeeName }}</strong>,
</p>

<p>
We are pleased to offer you the position of
<span class="highlight">{{ $offer->position }}</span>
at G.K. TECHNOLOGY.
</p>

<div class="section-box">

<strong>Offer Details:</strong>

<ul>

<li><strong>Employee Name:</strong> {{ $employeeName }}</li>

<li><strong>Employment Type:</strong> {{ ucfirst($offer->employment_type) }}</li>

<li><strong>Start Date:</strong> {{ optional($offer->start_date)->format('d M Y') }}</li>

@if($offer->employment_type === 'intern')

<li>
<strong>Stipend:</strong>
₹ {{ number_format($offer->stipend ?? 0,2) }} per month
</li>

@else

<li>
<strong>Salary:</strong>
₹ {{ number_format($offer->salary ?? 0,2) }} per annum
</li>

@endif

<li><strong>Location:</strong> {{ $offer->location }}</li>

</ul>





<div class="section-box">

<strong>Terms & Conditions:</strong>

<ul>

<li>This position does not guarantee permanent employment.</li>

<li>You must maintain confidentiality and professionalism at all times.</li>

<li>Either party may terminate employment with written notice.</li>

<li>You are expected to comply with company policies.</li>

</ul>

</div>

<p>
Please sign and return a copy of this letter to confirm your acceptance of the terms and conditions.
</p>

<table class="signature-table">

<tr>

<td width="50%">

<p><strong>Warm Regards,</strong></p>

<br><br>

<p>
<strong>HR Department</strong><br>
G.K. TECHNOLOGY
</p>

</td>

<td width="50%" align="right">

<p><strong>Accepted and Agreed:</strong></p>

<br><br><br>

<p>Signature: ________________________</p>
<p>Date: ________________________</p>

</td>

</tr>

</table>

<div class="divider"></div>

{{-- 🔥 SALARY BREAKDOWN ADDED --}}
@if($offer->employment_type === 'employee')

<h3 style="color:#d32f2f; margin-top:20px;">Salary Breakdown</h3>

<table width="100%" style="border-collapse: collapse; margin-top:10px; font-size:12px;" border="1">

<tr style="background:#f5f5f5; font-weight:bold;">
    <td style="padding:8px;">Earnings</td>
    <td style="padding:8px;" align="right">Amount (₹)</td>
    <td style="padding:8px;">Deductions</td>
    <td style="padding:8px;" align="right">Amount (₹)</td>
</tr>

<tr>
    <td style="padding:8px;">Basic Salary</td>
    <td align="right">{{ number_format($offer->basic_salary ?? 0, 2) }}</td>

    <td style="padding:8px;">Total Deductions</td>
    <td align="right">{{ number_format($offer->deductions ?? 0, 2) }}</td>
</tr>

<tr>
    <td style="padding:8px;">HRA</td>
    <td align="right">{{ number_format($offer->hra ?? 0, 2) }}</td>

    <td></td>
    <td></td>
</tr>

<tr>
    <td style="padding:8px;">Special Allowance</td>
    <td align="right">{{ number_format($offer->special_allowance ?? 0, 2) }}</td>

    <td></td>
    <td></td>
</tr>

<tr>
    <td style="padding:8px;">Other Allowances</td>
    <td align="right">{{ number_format($offer->other_allowance ?? 0, 2) }}</td>

    <td></td>
    <td></td>
</tr>

<tr>
    <td style="padding:8px;">Bonus</td>
    <td align="right">{{ number_format($offer->bonus ?? 0, 2) }}</td>

    <td></td>
    <td></td>
</tr>

{{-- TOTALS --}}
<tr style="font-weight:bold; background:#fafafa;">
    <td style="padding:8px;">Total Earnings</td>
    <td align="right">
        ₹ {{
            number_format(
                ($offer->basic_salary ?? 0)
                + ($offer->hra ?? 0)
                + ($offer->special_allowance ?? 0)
                + ($offer->other_allowance ?? 0)
                + ($offer->bonus ?? 0),
            2)
        }}
    </td>

    <td style="padding:8px;">Total Deductions</td>
    <td align="right">
        ₹ {{ number_format($offer->deductions ?? 0, 2) }}
    </td>
</tr>

</table>

{{-- NET SALARY --}}
<table width="100%" style="margin-top:15px; border:2px dashed #d32f2f;">
<tr>
<td style="padding:10px; font-weight:bold;">Net Salary Payable</td>

<td align="right" style="padding:10px; font-weight:bold; color:#d32f2f;">
₹ {{
    number_format(
        (
            ($offer->basic_salary ?? 0)
            + ($offer->hra ?? 0)
            + ($offer->special_allowance ?? 0)
            + ($offer->other_allowance ?? 0)
            + ($offer->bonus ?? 0)
        )
        - ($offer->deductions ?? 0),
    2)
}}
</td>

</tr>
</table>

@endif

<div class="annexure-title">
ANNEXURE-I
</div>




<ol>

<li>Proof of Age and current Address</li>
<li>PAN Card</li>
<li>Education Documents</li>
<li>Previous Employer Documents (if applicable)</li>
<li>Last 3 months Salary Slip / Bank Statement</li>
<li>One passport size color photograph</li>



</ol>

<div class="footer">
This is a system-generated offer letter.
</div>

</body>
</html>