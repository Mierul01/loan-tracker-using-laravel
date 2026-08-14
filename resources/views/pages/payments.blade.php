@extends('layouts.app')

@section('actions')
    <a class="btn btn-dark" href="{{ route('export.statement') }}">Export all PDF</a>
    <a class="btn btn-primary" href="{{ route('payments.create') }}">Add payment</a>
@endsection

@section('content')
    <div class="toolbar">
        <form class="search" method="GET" action="{{ route('payments.index') }}">
            <input type="search" name="q" value="{{ $search }}" placeholder="Search note or amount...">
            <button type="submit" class="btn btn-ghost">Search</button>
            @if($search !== '')
                <a class="btn btn-ghost" href="{{ route('payments.index') }}">Clear</a>
            @endif
        </form>
        <form action="{{ route('payment.reset') }}" method="POST" onsubmit="return confirm('Clear ALL payment data?')">
            @csrf
            <button type="submit" class="btn btn-danger">Reset all</button>
        </form>
    </div>

    <div class="stat-grid compact">
        <article class="stat-card compact">
            <span class="stat-label">Total paid</span>
            <strong>RM {{ number_format($totalPaid, 2) }}</strong>
        </article>
        <article class="stat-card compact">
            <span class="stat-label">Outstanding</span>
            <strong>RM {{ number_format($currentBalance, 2) }}</strong>
        </article>
        <article class="stat-card compact">
            <span class="stat-label">Entries</span>
            <strong>{{ $paymentCount }}</strong>
        </article>
        <article class="stat-card compact">
            <span class="stat-label">Avg payment</span>
            <strong>RM {{ number_format($averagePayment, 2) }}</strong>
        </article>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Balance after</th>
                        <th>Note</th>
                        <th>Document</th>
                        <th>PDF</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td data-label="Date">{{ optional($payment->paid_at)->format('Y-m-d') ?? $payment->created_at->format('Y-m-d') }}</td>
                            <td data-label="Amount">RM {{ number_format($payment->amount, 2) }}</td>
                            <td data-label="Balance">RM {{ number_format($payment->balance, 2) }}</td>
                            <td data-label="Note">{{ $payment->note ?: '—' }}</td>
                            <td data-label="Document">
                                @if($payment->document)
                                    <a class="text-link" href="{{ asset('storage/' . $payment->document) }}" target="_blank" rel="noopener">View</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td data-label="PDF">
                                <a class="btn btn-tiny" href="{{ route('payment.pdf', $payment->id) }}">Export</a>
                            </td>
                            <td data-label="Action">
                                <form action="{{ route('payment.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Delete this payment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">No payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="pager">
                {{ $payments->links('vendor.pagination.bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
