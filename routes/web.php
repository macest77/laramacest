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
// LottoController
Route::get('/lotto', [LottoController::class, 'index']);
Route::get('/lotto/wyniki', [LottoController::class, 'getdraw']);
Route::get('/lotto/getdrawdate', [LottoController::class, 'getdrawdate']);
Route::get('/lotto/przewidywania', [LottoController::class, 'countnext']);
