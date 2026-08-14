<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt #{{ $payment->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        .header { border-bottom: 2px solid #ea580c; padding-bottom: 12px; margin-bottom: 20px; }
        .brand { font-size: 22px; font-weight: bold; color: #111827; }
        .muted { color: #6b7280; }
        .box { border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .amount { font-size: 28px; font-weight: bold; color: #ea580c; }
        .footer { margin-top: 28px; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">LoanTrack</div>
        <div class="muted">Payment Receipt</div>
    </div>

    <div class="box">
        <div class="muted">Loan</div>
        <strong>{{ $settings->loan_title }}</strong><br>
        Borrower: {{ $settings->borrower_name }}
    </div>

    <p class="amount">RM {{ number_format($payment->amount, 2) }}</p>

    <table>
        <tr>
            <th>Receipt No.</th>
            <td>#{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <th>Payment Date</th>
            <td>{{ optional($payment->paid_at)->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Balance After</th>
            <td>RM {{ number_format($payment->balance, 2) }}</td>
        </tr>
        <tr>
            <th>Note</th>
            <td>{{ $payment->note ?: '-' }}</td>
        </tr>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, H:i') }} · LoanTrack Payment System
    </div>
</body>
</html>
