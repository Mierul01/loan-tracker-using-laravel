<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Payment Statement</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11px; }
        .header { border-bottom: 2px solid #ea580c; padding-bottom: 12px; margin-bottom: 18px; }
        .brand { font-size: 22px; font-weight: bold; }
        .muted { color: #6b7280; }
        .summary { width: 100%; margin-bottom: 18px; }
        .summary td { width: 33%; padding: 10px; border: 1px solid #e5e7eb; vertical-align: top; }
        .label { display: block; color: #6b7280; font-size: 10px; margin-bottom: 4px; }
        .value { font-size: 16px; font-weight: bold; }
        table.ledger { width: 100%; border-collapse: collapse; }
        table.ledger th { background: #f3f4f6; text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; }
        table.ledger td { padding: 8px; border-bottom: 1px solid #f3f4f6; }
        .footer { margin-top: 24px; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">LoanTrack</div>
        <div class="muted">Loan Payment Statement</div>
    </div>

    <p>
        <strong>{{ $settings->loan_title }}</strong><br>
        Borrower: {{ $settings->borrower_name }}<br>
        Start date: {{ $settings->start_date ? $settings->start_date->format('d M Y') : '-' }}
    </p>

    <table class="summary">
        <tr>
            <td>
                <span class="label">Total Loan</span>
                <span class="value">RM {{ number_format($totalLoan, 2) }}</span>
            </td>
            <td>
                <span class="label">Total Paid</span>
                <span class="value">RM {{ number_format($totalPaid, 2) }}</span>
            </td>
            <td>
                <span class="label">Outstanding</span>
                <span class="value">RM {{ number_format($currentBalance, 2) }}</span>
            </td>
        </tr>
    </table>

    <table class="ledger">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Amount (RM)</th>
                <th>Balance After (RM)</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($payment->paid_at)->format('Y-m-d') ?? $payment->created_at->format('Y-m-d') }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ number_format($payment->balance, 2) }}</td>
                    <td>{{ $payment->note ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No payments recorded.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ $generatedAt->format('d M Y, H:i') }} · LoanTrack Payment System
    </div>
</body>
</html>
