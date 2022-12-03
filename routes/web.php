<?php

use App\Http\Controllers\DuitkuController;
use App\Http\Controllers\IpaymuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XenditController;
use App\Http\Controllers\TripayController;

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

Route::get('/', function () {
    return view('welcome');
});

// Xendit Routes
Route::post('/xendit/callback', [XenditController::class, 'callback']);
Route::get('/xendit/payment-method', [XenditController::class, 'paymentMethod']);
Route::get('/xendit/checkout', [XenditController::class, 'createInvoice']);
Route::get('/xendit/invoice', [XenditController::class, 'allInvoice']);

// Tripay Routes
Route::get('/tripay/payment-method', [TripayController::class, 'channel']);
Route::get('/tripay/checkout', [TripayController::class, 'checkout']);
Route::get('/tripay/transaction', [TripayController::class, 'transaction']);

// Duitku Routes
Route::get('/duitku/checkout', [DuitkuController::class, 'checkout']);

// Ipaymu Routes
Route::get('/ipaymu/checkout', [IpaymuController::class, 'checkout']);
