<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\LoginEmailVerifyMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sign-up', [UserController::class, 'store']);

Route::get('verify-mail', [UserController::class, 'verify']);

Route::get('discard-mail', [UserController::class, 'discard']);

// Route::post('send-message', [UserController::class, 'sendMail']);
    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

    // Route::post('login', 'AuthController@login');

        Route::post('sign-in', [AuthController::class, 'login']);
    // Route::post('logout', 'AuthController@logout');
        Route::post('sign-out', [AuthController::class, 'logout']);
    // Route::post('refresh', 'AuthController@refresh');
        Route::post('refresh', [AuthController::class, 'refresh']);
    // Route::post('me', 'AuthController@me');
        Route::get('me', [AuthController::class, 'me']);

    });

Route::group(['middleware'=>'verified'], function() {
    Route::group(['middleware'=>'auth:api'], function() {
        Route::get('hospitals', [HospitalController::class, 'index']);
    });
});