@extends('layouts.app')

@section('actions')
    <a class="btn btn-primary" href="{{ route('payments.create') }}">Add payment</a>
@endsection

@section('content')
    <div class="stat-grid compact">
        <article class="stat-card compact">
            <span class="stat-label">Attached files</span>
            <strong>{{ $paymentsWithDocs }}</strong>
        </article>
        <article class="stat-card compact">
            <span class="stat-label">Total payments</span>
            <strong>{{ $paymentCount }}</strong>
        </article>
        <article class="stat-card compact">
            <span class="stat-label">Without receipt</span>
            <strong>{{ max(0, $paymentCount - $paymentsWithDocs) }}</strong>
        </article>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h2>Receipt archive</h2>
        </div>

        @if($documentPayments->isEmpty())
            <div class="empty">
                <p>No uploaded PDF receipts yet.</p>
                <a class="btn btn-primary" href="{{ route('payments.create') }}">Upload with a payment</a>
            </div>
        @else
            <div class="doc-grid">
                @foreach($documentPayments as $payment)
                    <article class="doc-card">
                        <div class="doc-icon">PDF</div>
                        <div class="doc-body">
                            <strong>RM {{ number_format($payment->amount, 2) }}</strong>
                            <p>{{ optional($payment->paid_at)->format('d M Y') ?? $payment->created_at->format('d M Y') }}</p>
                            <p>{{ $payment->note ?: 'Payment receipt' }}</p>
                        </div>
                        <div class="doc-actions">
                            <a class="btn btn-ghost" href="{{ asset('storage/' . $payment->document) }}" target="_blank" rel="noopener">Open</a>
                            <a class="btn btn-dark" href="{{ route('payment.pdf', $payment->id) }}">Export</a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($documentPayments->hasPages())
                <div class="pager">{{ $documentPayments->links('vendor.pagination.bootstrap-4') }}</div>
            @endif
        @endif
    </section>
@endsection
