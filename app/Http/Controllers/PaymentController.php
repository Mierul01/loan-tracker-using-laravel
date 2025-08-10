<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::latest()->paginate(5); 
        $totalLoan = 8000.00;
        $currentBalance = $totalLoan - $payments->sum('amount');

        return view('loan-tracker', compact('payments', 'totalLoan', 'currentBalance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'document' => 'nullable|mimes:pdf|max:2048',
        ]);

        $path = $request->hasFile('document') ? $request->file('document')->store('documents', 'public') : null;

        $latestPayment = Payment::latest()->first();
        $lastBalance = $latestPayment ? $latestPayment->balance : 8000.00;
        $newBalance = $lastBalance - $request->amount;

        Payment::create([
            'amount' => $request->amount,
            'balance' => $newBalance,
            'document' => $path,
        ]);

        return redirect('/');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        // Delete the file from storage
        if ($payment->document && \Storage::disk('public')->exists($payment->document)) {
            \Storage::disk('public')->delete($payment->document);
        }

        $payment->delete();

        return redirect('/');
    }

    public function reset()
    {
        // Optional: delete associated documents
        $payments = Payment::all();
        foreach ($payments as $payment) {
            if ($payment->document && \Storage::disk('public')->exists($payment->document)) {
                \Storage::disk('public')->delete($payment->document);
            }
        }

        // Delete all payment records
        Payment::truncate();

        return redirect('/')->with('success', 'All payment data has been cleared.');
    }

}
