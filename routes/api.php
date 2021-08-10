<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\RolesController;
use App\Http\Controllers\Api\Admin\SectionsController;
use App\Http\Controllers\Api\Admin\PermissionsController;
use App\Http\Controllers\Api\User\ArticleController;
use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\User\SubmitToPendingArticleController;
use App\Http\Controllers\Api\User\PendingArticleController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Public\GetArticle;
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

Route::group([], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('signup', [UserController::class, 'signUp']);
    Route::resource('article', GetArticle::class);
});

Route::group(['middleware' => ['checkUserToken', 'permission:admin'], 'prefix' => 'admin'], function () {
    Route::resource('roles', RolesController::class);
    Route::resource('users', UsersController::class);
    Route::get('usersPages', [UsersController::class, 'usersPages']);
    Route::post('usersSearch', [UsersController::class, 'search']);
    Route::resource('sections', SectionsController::class);
    Route::resource('permissions', PermissionsController::class);
});


Route::group(['prefix' => 'dashboard', 'middleware' => 'checkUserToken'], function () {
    Route::resource('user', UserController::class);
    Route::resource('articles', ArticleController::class)->middleware('permission:publisher,admin');
    Route::resource('submitToPendingArticles', SubmitToPendingArticleController::class)->middleware('permission:admin,writer');
    Route::resource('pendingArticle', PendingArticleController::class)->middleware('permission:approve,admin');
});
