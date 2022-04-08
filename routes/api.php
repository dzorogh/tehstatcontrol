<?php

use App\Http\Controllers\ApiLogoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\StatsController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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


Route::middleware('auth')->group(function() {
    Route::get('/page/{page}', [PageController::class, 'show']);
    Route::get('/news', [PageController::class, 'news']);
    Route::get('/news-types', [PageController::class, 'newsTypes']);

    Route::get('/stats/groups', [StatsController::class, 'groups']);
    Route::get('/stats/groups/{group}', [StatsController::class, 'group']);
    Route::post('/stats/products', [StatsController::class, 'products']);

    Route::get('/products/', [CompareController::class, 'getList']);

    Route::get('/logout', ApiLogoutController::class);
});

Route::get('/check-auth', function (\Illuminate\Http\Request $request) {
    $value = $request->session()->all();

    if (Auth::check()) {
        return response('', 200);
    } else {
        return response('', 401);
    }
});
