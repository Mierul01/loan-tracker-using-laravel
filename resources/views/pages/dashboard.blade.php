@extends('layouts.app')

@section('actions')
    <a class="btn btn-dark" href="{{ route('export.statement') }}">Export PDF</a>
    <a class="btn btn-primary" href="{{ route('payments.create') }}">Add payment</a>
@endsection

@section('content')
    <div class="stat-grid">
        <article class="stat-card">
            <span class="stat-label">Total loan</span>
            <strong class="stat-value">RM {{ number_format($totalLoan, 2) }}</strong>
            <span class="stat-note">Configured loan amount</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Paid revenue</span>
            <strong class="stat-value">RM {{ number_format($totalPaid, 2) }}</strong>
            <span class="stat-note">From {{ $paymentCount }} payment{{ $paymentCount === 1 ? '' : 's' }}</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Outstanding</span>
            <strong class="stat-value">RM {{ number_format($currentBalance, 2) }}</strong>
            <span class="stat-note">{{ $status }}</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Progress</span>
            <strong class="stat-value">{{ $progressPercent }}%</strong>
            <span class="stat-note">This month RM {{ number_format($monthlyPaid, 2) }}</span>
        </article>
    </div>

    <div class="dash-split">
        <section class="panel">
            <div class="panel-head">
                <h2>Loan progress</h2>
                <span class="badge badge-{{ $status === 'Fully paid' ? 'green' : ($status === 'In progress' ? 'orange' : 'gray') }}">{{ $status }}</span>
            </div>

            <div class="loan-row">
                <div>
                    <strong>{{ $settings->loan_title }}</strong>
                    <p>{{ $settings->borrower_name }} · started {{ $settings->start_date ? $settings->start_date->format('d M Y') : '—' }}</p>
                </div>
                <div class="loan-meta">RM {{ number_format($totalPaid, 2) }} / RM {{ number_format($totalLoan, 2) }}</div>
            </div>

            <div class="progress"><div class="progress-bar" style="width: {{ $progressPercent }}%"></div></div>

            <div class="mini-stats">
                <div><span>Avg payment</span><strong>RM {{ number_format($averagePayment, 2) }}</strong></div>
                <div><span>Documents</span><strong>{{ $paymentsWithDocs }}</strong></div>
                <div><span>Remaining</span><strong>RM {{ number_format($currentBalance, 2) }}</strong></div>
            </div>

            <div class="panel-actions">
                <a class="btn btn-ghost" href="{{ route('payments.index') }}">View payments</a>
                <a class="btn btn-dark" href="{{ route('export.statement') }}">Download statement PDF</a>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Quick actions</h2>
            </div>
            <div class="action-list">
                <a href="{{ route('payments.create') }}" class="action-item">
                    <div>
                        <strong>Record payment</strong>
                        <p>Log amount, note, and receipt</p>
                    </div>
                    <span class="badge badge-orange">New</span>
                </a>
                <a href="{{ route('export.statement') }}" class="action-item">
                    <div>
                        <strong>Export statement</strong>
                        <p>Download full payment PDF</p>
                    </div>
                    <span class="badge badge-blue">Report</span>
                </a>
                <a href="{{ route('documents.index') }}" class="action-item">
                    <div>
                        <strong>Review receipts</strong>
                        <p>{{ $paymentsWithDocs }} PDF document{{ $paymentsWithDocs === 1 ? '' : 's' }} attached</p>
                    </div>
                    <span class="badge badge-green">Docs</span>
                </a>
                <a href="{{ route('settings.index') }}" class="action-item">
                    <div>
                        <strong>Loan setup</strong>
                        <p>Update borrower and loan amount</p>
                    </div>
                    <span class="badge badge-gray">Setup</span>
                </a>
            </div>
        </section>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h2>Recent payments</h2>
            <a class="text-link" href="{{ route('payments.index') }}">See all</a>
        </div>

        @if($recentPayments->isEmpty())
            <div class="empty">
                <p>No payments yet.</p>
                <a class="btn btn-primary" href="{{ route('payments.create') }}">Add first payment</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Note</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayments as $payment)
                            <tr>
                                <td>{{ optional($payment->paid_at)->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td>
                                <td>RM {{ number_format($payment->amount, 2) }}</td>
                                <td>RM {{ number_format($payment->balance, 2) }}</td>
                                <td>{{ $payment->note ?: '—' }}</td>
                                <td><a class="text-link" href="{{ route('payment.pdf', $payment->id) }}">Export PDF</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
