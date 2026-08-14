@extends('layouts.app')

@section('actions')
    <a class="btn btn-dark" href="{{ route('export.statement') }}">Download statement</a>
@endsection

@section('content')
    <div class="stat-grid">
        <article class="stat-card">
            <span class="stat-label">Statement PDF</span>
            <strong class="stat-value">Full history</strong>
            <span class="stat-note">All payments with totals and outstanding balance</span>
            <a class="btn btn-primary" href="{{ route('export.statement') }}">Download statement</a>
        </article>
        <article class="stat-card">
            <span class="stat-label">Receipt PDF</span>
            <strong class="stat-value">Per payment</strong>
            <span class="stat-note">Export any payment from the payments list</span>
            <a class="btn btn-dark" href="{{ route('payments.index') }}">Open payments</a>
        </article>
        <article class="stat-card">
            <span class="stat-label">Total paid</span>
            <strong class="stat-value">RM {{ number_format($totalPaid, 2) }}</strong>
            <span class="stat-note">{{ $paymentCount }} recorded payment{{ $paymentCount === 1 ? '' : 's' }}</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Outstanding</span>
            <strong class="stat-value">RM {{ number_format($currentBalance, 2) }}</strong>
            <span class="stat-note">{{ $progressPercent }}% repaid</span>
        </article>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h2>Statement preview</h2>
            <span class="muted">{{ $settings->loan_title }} · {{ $settings->borrower_name }}</span>
        </div>

        <div class="summary-strip">
            <div><span>Total loan</span><strong>RM {{ number_format($totalLoan, 2) }}</strong></div>
            <div><span>Total paid</span><strong>RM {{ number_format($totalPaid, 2) }}</strong></div>
            <div><span>Outstanding</span><strong>RM {{ number_format($currentBalance, 2) }}</strong></div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Balance after</th>
                        <th>Note</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allPayments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($payment->paid_at)->format('Y-m-d') ?? $payment->created_at->format('Y-m-d') }}</td>
                            <td>RM {{ number_format($payment->amount, 2) }}</td>
                            <td>RM {{ number_format($payment->balance, 2) }}</td>
                            <td>{{ $payment->note ?: '—' }}</td>
                            <td><a class="text-link" href="{{ route('payment.pdf', $payment->id) }}">Receipt PDF</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No payments to report yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
