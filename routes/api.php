<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\RolesController;
use App\Http\Controllers\Api\Admin\SectionsController;
use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\User\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
    //routes
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['checkUserToken', 'role:admin'], 'prefix' => 'admin'], function () {
    Route::resource('roles', RolesController::class);
    Route::resource('users', UsersController::class);
    Route::resource('sections', SectionsController::class);
});

Route::group(['prefix' => 'user', 'middleware' => 'auth.guard:user-api'], function () {
    Route::post('profile', function () {
        return 'only auth user can reach me';
    });
});

Route::group(['prefix' => 'user'], function () {
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('logout', [UserAuthController::class, 'logout']);
});

Route::group(['middleware' => ['api', 'checkPassword', 'changeLang', 'CheckUserToken:user-api'], 'namespace' => 'Api'], function () {
    Route::get('offers', [CategoriesController::class, 'index']);
});
