<?php

use App\Events\OrderCreated;
use App\Events\OrderUpdate;
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
Route::get('/transaction/{invoice}/status', [TransactionController::class, 'getOrderStatus'])->name('transaction.status');
Route::post('/transaction/callback-handler', [TripayController::class, 'handleCallback'])->name('transaction.status.update');


// test pusher
Route::get('/test', function () {
    $data = json_decode('{
        "id": "123",
        "status": "PAID"
    }');
    OrderUpdate::dispatch($data);
    return "Order Updated";
});
Route::get('/test2', function () {
    OrderCreated::dispatch('Order Created');
    return "Order Created";
});
