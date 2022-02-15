<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginWithBitrixController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/bitrix-login', LoginWithBitrixController::class);
Route::get('/{page}', [HomeController::class, "show"])->where('page', '^(?!admin|nova-api|nova-vendor|api|storage|bitrix-login|logout|check-auth).*$');


