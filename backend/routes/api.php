<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

Route::get('/payment-method', [PaymentMethodController::class, 'index'])->name('payment-method.index');
Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');

    Route::get('/customer/{customer}', [CustomerController::class, 'show'])->name('customer.show');
    Route::put('/customer/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
});
