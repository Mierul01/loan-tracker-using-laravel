@extends('layouts.app')

@section('actions')
    <a class="btn btn-ghost" href="{{ route('dashboard') }}">Back to dashboard</a>
@endsection

@section('content')
    <div class="form-layout">
        <section class="panel">
            <div class="panel-head">
                <h2>Loan setup</h2>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" class="form">
                @csrf
                <div class="fields-2">
                    <div class="field">
                        <label for="borrower_name">Borrower name</label>
                        <input type="text" name="borrower_name" id="borrower_name" required value="{{ old('borrower_name', $settings->borrower_name) }}">
                    </div>
                    <div class="field">
                        <label for="loan_title">Loan title</label>
                        <input type="text" name="loan_title" id="loan_title" required value="{{ old('loan_title', $settings->loan_title) }}">
                    </div>
                    <div class="field">
                        <label for="total_loan">Total loan (RM)</label>
                        <input type="text" name="total_loan" id="total_loan" required value="{{ old('total_loan', number_format($settings->total_loan, 2, '.', '')) }}">
                    </div>
                    <div class="field">
                        <label for="start_date">Start date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', optional($settings->start_date)->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="field">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="4">{{ old('notes', $settings->notes) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save settings</button>
            </form>
        </section>

        <aside class="panel tip-panel">
            <h2>Current snapshot</h2>
            <div class="tip-summary">
                <div><span>Status</span><strong>{{ $status }}</strong></div>
                <div><span>Paid</span><strong>RM {{ number_format($totalPaid, 2) }}</strong></div>
                <div><span>Outstanding</span><strong>RM {{ number_format($currentBalance, 2) }}</strong></div>
                <div><span>Progress</span><strong>{{ $progressPercent }}%</strong></div>
            </div>
            <p class="tip-note">Changing the total loan amount recalculates all payment balances.</p>
        </aside>
    </div>
@endsection
