<?php

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

Route::post('api_gtip',  [App\Http\Controllers\GtipController::class, 'apiGtip'])->name('api_gtip');

Route::post('selectCity',  [App\Http\Controllers\LocationController::class, 'selectCity'])->name('selectCity');

Route::post('selectSingleCountry',  [App\Http\Controllers\LocationController::class, 'selectSingleCountry'])->name('selectSingleCountry');
Route::post('selectSingleState',  [App\Http\Controllers\LocationController::class, 'selectSingleState'])->name('selectSingleState');
Route::post('selectSingleCity',  [App\Http\Controllers\LocationController::class, 'selectSingleCity'])->name('selectSingleCity');

