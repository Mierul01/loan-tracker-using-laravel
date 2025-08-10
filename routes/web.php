<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PaymentController::class, 'index']);
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
Route::delete('/payment/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');
Route::post('/payment/reset', [PaymentController::class, 'reset'])->name('payment.reset');

