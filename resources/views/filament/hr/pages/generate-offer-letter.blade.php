<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offer Letter</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.6;
        }
        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="title">Offer Letter</div>

<p>Date: {{ now()->format('d M Y') }}</p>

<p>Dear {{ $offer->user->name }},</p>

<p>
We are pleased to offer you the position of 
<strong>{{ $offer->position }}</strong> 
at our {{ $offer->location }} office.
</p>

@if($offer->employment_type === 'employee')
<p>Your annual salary will be ₹{{ number_format($offer->salary, 2) }}.</p>
@else
<p>You will receive a stipend of ₹{{ number_format($offer->stipend, 2) }} per month.</p>
@endif

@if($offer->duration)
<p>Duration: {{ $offer->duration }}</p>
@endif

<p>
We look forward to working with you.
</p>

<br><br>

<p>
Regards,<br>
HR Department
</p>

</body>
</html>