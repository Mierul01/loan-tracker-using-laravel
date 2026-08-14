@extends('layouts.app')

@section('actions')
    <a class="btn btn-ghost" href="{{ route('payments.index') }}">Back to payments</a>
@endsection

@section('content')
    <div class="form-layout">
        <section class="panel">
            <div class="panel-head">
                <h2>Payment details</h2>
                <span class="muted">Remaining RM {{ number_format($currentBalance, 2) }}</span>
            </div>

            <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                <div class="fields-2">
                    <div class="field">
                        <label for="amount">Amount (RM)</label>
                        <input type="text" name="amount" id="amount" inputmode="decimal" required placeholder="0.00" value="{{ old('amount') }}">
                    </div>
                    <div class="field">
                        <label for="paid_at">Payment date</label>
                        <input type="date" name="paid_at" id="paid_at" value="{{ old('paid_at', now()->toDateString()) }}">
                    </div>
                </div>
                <div class="field">
                    <label for="note">Note</label>
                    <input type="text" name="note" id="note" maxlength="255" placeholder="e.g. March installment" value="{{ old('note') }}">
                </div>
                <div class="field">
                    <label for="document">Receipt PDF (optional)</label>
                    <input type="file" name="document" id="document" accept="application/pdf">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" @if($currentBalance <= 0) disabled @endif>
                        {{ $currentBalance <= 0 ? 'Loan fully paid' : 'Save payment' }}
                    </button>
                </div>
            </form>
        </section>

        <aside class="panel tip-panel">
            <h2>Before saving</h2>
            <ul>
                <li>Amount cannot exceed remaining balance.</li>
                <li>Each payment can be exported as a PDF receipt.</li>
                <li>Uploaded receipts appear under Documents.</li>
            </ul>
            <div class="tip-summary">
                <div><span>Total loan</span><strong>RM {{ number_format($totalLoan, 2) }}</strong></div>
                <div><span>Paid</span><strong>RM {{ number_format($totalPaid, 2) }}</strong></div>
                <div><span>Outstanding</span><strong>RM {{ number_format($currentBalance, 2) }}</strong></div>
            </div>
        </aside>
    </div>
@endsection
