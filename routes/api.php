<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TripayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductController::class, 'exportProducts'])->name('products.export');
Route::post('/create-orders', [TransactionController::class, 'create'])->name('transaction.create');
Route::get('/merchant-channel', [TripayController::class, 'merchantChannel'])->name('merchant-channel');
Route::get('/transaction/{invoice}', [TripayController::class, 'getOrderDetails'])->name('transaction.detail');
