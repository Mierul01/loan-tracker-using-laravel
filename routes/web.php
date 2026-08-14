<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', [PaymentController::class, 'dashboard'])->name('dashboard');
Route::get('/payments', [PaymentController::class, 'payments'])->name('payments.index');
Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments', [PaymentController::class, 'store'])->name('payment.store');
Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');
Route::post('/payments/reset', [PaymentController::class, 'reset'])->name('payment.reset');
Route::get('/payments/{id}/pdf', [PaymentController::class, 'exportPayment'])->name('payment.pdf');

Route::get('/documents', [PaymentController::class, 'documents'])->name('documents.index');
Route::get('/reports', [PaymentController::class, 'reports'])->name('reports.index');
Route::get('/reports/statement.pdf', [PaymentController::class, 'exportStatement'])->name('export.statement');

Route::get('/settings', [PaymentController::class, 'settings'])->name('settings.index');
Route::post('/settings', [PaymentController::class, 'updateSettings'])->name('settings.update');
