<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortnerController;

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

Route::post('/tiny-url', [UrlShortnerController::class, 'create']);
ROute::get('/urls', [UrlShortnerController::class, 'retrieve']);
