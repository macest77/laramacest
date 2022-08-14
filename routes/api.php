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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('getlastdraw', [App\Http\Controllers\ApiLottoController::class, 'getlastdraw']);
Route::get('getdraw/{date}', [App\Http\Controllers\ApiLottoController::class, 'getdraw']);
Route::get('getlaststanding', [App\Http\Controllers\ApiMusicController::class, 'getlaststanding']);
Route::get('getstanding/{id}', [App\Http\Controllers\ApiMusicController::class, 'getstanding']);
Route::get('band/{id}', [App\Http\Controllers\ApiDataController::class, 'getBandData']);