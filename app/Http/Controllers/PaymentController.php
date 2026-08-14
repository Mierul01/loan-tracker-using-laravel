<?php

namespace App\Http\Controllers;

use App\Models\LoanSetting;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function dashboard()
    {
        $data = $this->summary();
        $data['recentPayments'] = Payment::orderByDesc('paid_at')->orderByDesc('id')->limit(6)->get();
        $data['monthlyPaid'] = Payment::whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');
        $data['activeNav'] = 'dashboard';
        $data['pageTitle'] = 'Dashboard';

        return view('pages.dashboard', $data);
    }

    public function payments(Request $request)
    {
        $data = $this->summary();
        $search = trim((string) $request->get('q', ''));

        $query = Payment::query()->orderByDesc('paid_at')->orderByDesc('id');
        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('note', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%');
            });
        }

        $data['payments'] = $query->paginate(10)->withQueryString();
        $data['search'] = $search;
        $data['activeNav'] = 'payments';
        $data['pageTitle'] = 'Payments';

        return view('pages.payments', $data);
    }

    public function create()
    {
        $data = $this->summary();
        $data['activeNav'] = 'create';
        $data['pageTitle'] = 'Add Payment';

        return view('pages.create', $data);
    }

    public function store(Request $request)
    {
        $settings = LoanSetting::current();
        $totalLoan = (float) $settings->total_loan;
        $totalPaid = (float) Payment::sum('amount');
        $remaining = max(0, $totalLoan - $totalPaid);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . max($remaining, 0.01),
            'paid_at' => 'nullable|date',
            'note' => 'nullable|string|max:255',
            'document' => 'nullable|mimes:pdf|max:2048',
        ], [
            'amount.max' => 'Payment cannot exceed the remaining balance of RM ' . number_format($remaining, 2) . '.',
        ]);

        $path = $request->hasFile('document')
            ? $request->file('document')->store('documents', 'public')
            : null;

        $amount = (float) $request->amount;

        Payment::create([
            'amount' => $amount,
            'balance' => max(0, round($remaining - $amount, 2)),
            'document' => $path,
            'note' => $request->note,
            'paid_at' => $request->paid_at ?: now()->toDateString(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function documents()
    {
        $data = $this->summary();
        $data['documentPayments'] = Payment::whereNotNull('document')
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->paginate(12);
        $data['activeNav'] = 'documents';
        $data['pageTitle'] = 'Documents';

        return view('pages.documents', $data);
    }

    public function reports()
    {
        $data = $this->summary();
        $data['allPayments'] = Payment::orderBy('paid_at')->orderBy('id')->get();
        $data['activeNav'] = 'reports';
        $data['pageTitle'] = 'Reports';

        return view('pages.reports', $data);
    }

    public function settings()
    {
        $data = $this->summary();
        $data['activeNav'] = 'settings';
        $data['pageTitle'] = 'Settings';

        return view('pages.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:120',
            'loan_title' => 'required|string|max:120',
            'total_loan' => 'required|numeric|min:0.01',
            'start_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        LoanSetting::current()->update([
            'borrower_name' => $request->borrower_name,
            'loan_title' => $request->loan_title,
            'total_loan' => $request->total_loan,
            'start_date' => $request->start_date,
            'notes' => $request->notes,
        ]);

        $this->recalculateBalances();

        return redirect()->route('settings.index')->with('success', 'Loan settings updated.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->document && Storage::disk('public')->exists($payment->document)) {
            Storage::disk('public')->delete($payment->document);
        }

        $payment->delete();
        $this->recalculateBalances();

        return redirect()->route('payments.index')->with('success', 'Payment deleted and balances recalculated.');
    }

    public function reset()
    {
        foreach (Payment::all() as $payment) {
            if ($payment->document && Storage::disk('public')->exists($payment->document)) {
                Storage::disk('public')->delete($payment->document);
            }
        }

        Payment::truncate();

        return redirect()->route('dashboard')->with('success', 'All payment data has been cleared.');
    }

    public function exportPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $settings = LoanSetting::current();

        return Pdf::loadView('pdf.payment-receipt', compact('payment', 'settings'))
            ->setPaper('a4')
            ->download('payment-receipt-' . $payment->id . '.pdf');
    }

    public function exportStatement()
    {
        $settings = LoanSetting::current();
        $payments = Payment::orderBy('paid_at')->orderBy('id')->get();
        $totalLoan = (float) $settings->total_loan;
        $totalPaid = (float) $payments->sum('amount');
        $currentBalance = max(0, round($totalLoan - $totalPaid, 2));
        $generatedAt = now();

        return Pdf::loadView('pdf.payment-statement', compact(
            'settings',
            'payments',
            'totalLoan',
            'totalPaid',
            'currentBalance',
            'generatedAt'
        ))->setPaper('a4')->download('loan-payment-statement.pdf');
    }

    protected function summary()
    {
        $settings = LoanSetting::current();
        $totalLoan = (float) $settings->total_loan;
        $totalPaid = (float) Payment::sum('amount');
        $currentBalance = max(0, round($totalLoan - $totalPaid, 2));
        $paymentCount = Payment::count();
        $progressPercent = $totalLoan > 0
            ? min(100, round(($totalPaid / $totalLoan) * 100, 1))
            : 0;
        $averagePayment = $paymentCount > 0
            ? round($totalPaid / $paymentCount, 2)
            : 0;
        $paymentsWithDocs = Payment::whereNotNull('document')->count();

        $status = 'Not started';
        if ($paymentCount > 0 && $currentBalance <= 0) {
            $status = 'Fully paid';
        } elseif ($paymentCount > 0) {
            $status = 'In progress';
        }

        return compact(
            'settings',
            'totalLoan',
            'totalPaid',
            'currentBalance',
            'paymentCount',
            'progressPercent',
            'averagePayment',
            'paymentsWithDocs',
            'status'
        );
    }

    protected function recalculateBalances()
    {
        $settings = LoanSetting::current();
        $running = (float) $settings->total_loan;

        DB::transaction(function () use (&$running) {
            foreach (Payment::orderBy('paid_at')->orderBy('id')->get() as $payment) {
                $running = round($running - (float) $payment->amount, 2);
                $payment->balance = max(0, $running);
                $payment->save();
            }
        });
    }
}
