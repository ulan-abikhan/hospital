<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\LoginEmailVerifyMiddleware;
use App\Models\Hospital;
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
        Route::get('departments', [DepartmentController::class, 'index']);
        Route::get('departments/{department_id}/services', 
        [ServiceController::class, 'index']);
        Route::get('services/{id}', [ServiceController::class, 'show']);

        Route::group(['middleware'=>CheckAdmin::class], function() {
            Route::post('hospitals', [HospitalController::class, 'store']);
            Route::patch('hospitals/{id}', [HospitalController::class, 'update']);
            Route::delete('hospitals/{id}', [HospitalController::class, 'destroy']);

            Route::post('departments', [DepartmentController::class, 'store']);
            Route::patch('departments/{id}', [DepartmentController::class, 'update']);
            Route::delete('departments/{id}', [DepartmentController::class, 'destroy']);

            Route::post('doctors', [DoctorController::class, 'store']);
            Route::patch('doctors/{id}', [DoctorController::class, 'update']);
            Route::delete('doctors/{id}', [DoctorController::class, 'destroy']);

            Route::post('services', [ServiceController::class, 'store']);
            Route::patch('services/{id}', [ServiceController::class, 'update']);
            Route::delete('services/{id}', [ServiceController::class, 'destroy']);
        });

    });
});