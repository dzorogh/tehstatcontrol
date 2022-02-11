<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\StatsController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::get('/page/{page}', [PageController::class, 'show']);
Route::get('/news', [PageController::class, 'news']);

Route::get('/stats/groups', [StatsController::class, 'groups']);
Route::get('/stats/groups/{group}', [StatsController::class, 'group']);
Route::post('/stats/products', [StatsController::class, 'products']);
