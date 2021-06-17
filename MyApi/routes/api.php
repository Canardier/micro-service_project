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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/home', function () {
    return response('Hello World', 200)
        ->header('Content-Type', 'text/plain');
});

Route::prefix('user')->group(function () {
    Route::post('/', [\App\Http\Controllers\UserController::class, 'store']);
    Route::delete('/{id}', [\App\Http\Controllers\UserController::class, 'destroy']);
    Route::post('/{id}/video', [\App\Http\Controllers\VideoController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::get('/{id}', [\App\Http\Controllers\UserController::class, 'show_by_id']);
    Route::get('/{id}/videos', [\App\Http\Controllers\VideoController::class, 'show_by_id']);
});

Route::prefix('video')->group(function () {
    Route::delete('/{id}', [\App\Http\Controllers\VideoController::class, 'destroy']);
    Route::post('/{id}/comment', [\App\Http\Controllers\CommentsController::class, 'store']);
    Route::get('/{id}/comments', [\App\Http\Controllers\CommentsController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\VideoController::class, 'update']);
    Route::patch('/{id}', [\App\Http\Controllers\VideoController::class, 'encode']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'show']);
});

Route::get('/videos', [\App\Http\Controllers\VideoController::class, 'show']);
Route::post('/auth', [\App\Http\Controllers\UserController::class, 'login']);
