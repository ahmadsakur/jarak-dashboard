<?php

use App\Http\Controllers\ProductController;
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

Route::get('/products', [ProductController::class, 'tests'])->name('products');
Route::post('/orders', [ProductController::class, 'orders'])->name('orders');
Route::get('/merchant-channel', [TripayController::class, 'merchantChannel'])->name('merchant-channel');
Route::get('/transaction/{invoice}', [TripayController::class, 'getOrderDetails'])->name('transaction.detail');
Route::get('/variant/{id}', [ProductController::class, 'getVariantDetails'])->name('variant.detail');

