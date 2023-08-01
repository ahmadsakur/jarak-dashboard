<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VariantController;
use Illuminate\Support\Facades\Route;

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
	return redirect('/dashboard');
})->middleware('auth');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');


Route::group(['middleware' => 'auth'], function () {
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
	Route::resource('category', CategoryController::class);
	Route::resource('product', ProductController::class);
	Route::resource('variant', VariantController::class);
	Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index');
	Route::patch('/transactions/{id}', [TransactionController::class, 'updateOrderStatus'])->name('transaction.update');
	Route::get('/transactions/{id}', [TransactionController::class, 'getOrderItems'])->name('transaction.detail');
	Route::get('/transactions-week', [TransactionController::class, 'getWeeklyTransaction'])->name('transaction.weekly');
	Route::get('/transactions-month', [TransactionController::class, 'getMonthlyTransaction'])->name('transaction.monthly');
	Route::get('/transactions-all', [TransactionController::class, 'getAllTransactions'])->name('transaction.all');
});
