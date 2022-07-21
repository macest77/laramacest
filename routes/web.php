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
use App\Http\Controllers\LottoController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\StandingsController;
// LottoController
Route::get('/lotto', [LottoController::class, 'index']);
Route::get('/lotto/wyniki', [LottoController::class, 'getdraw']);
Route::get('/lotto/getdrawdate', [LottoController::class, 'getdrawdate']);
Route::get('/lotto/przewidywania', [LottoController::class, 'countnext']);
Route::get('/reviews', [MusicController::class, 'index']);
Route::get('/lista-hard-n-heavy', [MusicController::class, 'list']);
Route::post('/lista-hard-n-heavy', [MusicController::class, 'listpost']);
Route::get('/confirmcode', [MusicController::class, 'confirmCode']);
Route::get('/chart', [MusicController::class, 'chart']);
Route::post('/chart', [MusicController::class, 'postchart']);
Route::post('/addsong', [StandingsController::class, 'addsongpost']);
Route::get('/addsong', [StandingsController::class, 'addsong']);
Route::get('/list/{id}', [MusicController::class, 'listing']);
Route::get('/little/', [StandingsController::class, 'countlittlepoints']);