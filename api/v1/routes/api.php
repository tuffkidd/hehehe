<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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


Route::post('/auth/register', [AuthController::class, 'register']); // https://qualara.test/api/v1/auth/register
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('/user', function (Request $request) {
    //     return $request->user();
    // });

    Route::get('/auth/user', [AuthController::class, 'user']);

    Route::get('/auth/get_users', [AuthController::class, 'get_users']); // https://qualara.test/api/v1/auth/get_users

    Route::get('/auth/logout', [AuthController::class, 'logout']);
    // Route::post('/auth/isValidToken', [AuthController::class, 'isValidToken']);
});
