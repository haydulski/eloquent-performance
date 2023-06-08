<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::controller(UserController::class)->prefix('/users')->name('users.')->group( function(){
    Route::get('bad', 'bad')->name('bad');
    Route::get('better', 'better')->name('better');
    Route::get('good','good')->name('good');
    Route::get('best', 'best')->name('best');
    Route::get('status-bad','statusBad')->name('status.bad');
    Route::get('status-good','statusGood')->name('status.good');
});
