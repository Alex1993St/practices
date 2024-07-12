<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ElasticSearchController;
use \App\Http\Controllers\ElasticController;

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

Route::get('elastic', [ElasticController::class, 'index'])->name('elastic.index');
Route::post('elastic/store', [ElasticController::class, 'store'])->name('elastic.store');
Route::delete('elastic/delete', [ElasticController::class, 'delete'])->name('elastic.delete');

Route::get('practice_elastic', [ElasticSearchController::class, 'index']);

