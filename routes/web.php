<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'stocks'], function(){
      Route::get('/data/{stock}', [App\Http\Controllers\StockController::class, 'data'])->name('stock.data');
      Route::get('/index', [App\Http\Controllers\StockController::class, 'index'])->name('stock.index');
      Route::get('/show/{stock}', [App\Http\Controllers\StockController::class, 'show'])->name('stock.show');
        Route::get('/create', [App\Http\Controllers\StockController::class, 'create'])->name('stock.create');
        Route::get('/edit/{stock}', [App\Http\Controllers\StockController::class, 'edit'])->name('stock.edit');
        Route::post('/update/{stock}', [App\Http\Controllers\StockController::class, 'update'])->name('stock.update');
        Route::post('/store/', [App\Http\Controllers\StockController::class, 'store'])->name('stock.store');
        Route::post('/destroy/{stock}', [App\Http\Controllers\StockController::class, 'destroy'])->name('stock.destroy');
      });
  
    });


    Route::group(['middleware' => ['auth']], function () {
      Route::group(['prefix' => 'orders'], function(){
        Route::get('/index', [App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
        Route::get('/show/{stock}', [App\Http\Controllers\OrderController::class, 'show'])->name('order.show');
          Route::get('/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
          Route::get('/edit/{stock}', [App\Http\Controllers\OrderController::class, 'edit'])->name('order.edit');
          Route::post('/update/{stock}', [App\Http\Controllers\OrderController::class, 'update'])->name('order.update');
          Route::post('/store/{stock}', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
          Route::post('/destroy/{stock}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('order.destroy');
        });
    
      });





    Route::group(['middleware' => ['auth']], function () {
      Route::group(['prefix' => 'personal'], function(){
        Route::get('/index', [App\Http\Controllers\UserStockController::class, 'index'])->name('personal.index');
        // Route::get('/show/{stock}', [App\Http\Controllers\UserStockController::class, 'show'])->name('stock.show');
          // Route::get('/create', [App\Http\Controllers\UserStockController::class, 'create'])->name('stock.create');
          // Route::get('/edit/{stock}', [App\Http\Controllers\UserStockController::class, 'edit'])->name('stock.edit');
          // Route::post('/update/{stock}', [App\Http\Controllers\UserStockController::class, 'update'])->name('stock.update');
          // Route::post('/store/', [App\Http\Controllers\UserStockController::class, 'store'])->name('stock.store');
          // Route::post('/destroy/{stock}', [App\Http\Controllers\UserStockController::class, 'destroy'])->name('stock.destroy');
        });
    
      });