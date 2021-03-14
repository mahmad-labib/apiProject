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
use App\Http\Controllers\Api\User\RegisterController;
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

Route::group(['middleware' => ['checkUserToken', 'permission:admin'], 'prefix' => 'admin'], function () {
    Route::resource('roles', RolesController::class);
    Route::resource('users', UsersController::class);
    Route::resource('sections', SectionsController::class);
    Route::resource('permissions', PermissionsController::class);
});



Route::group(['prefix' => 'dashboard'], function () {
    Route::post('user/register', [RegisterController::class, 'register']);
});

Route::post('signup', [UserController::class, 'signUp']);

Route::group(['prefix' => 'dashboard', 'middleware' => 'checkUserToken'], function () {
    Route::resource('user', UserController::class);
    Route::resource('articles', ArticleController::class)->middleware('permission:publisher,admin');
    Route::resource('submitToPendingArticles', SubmitToPendingArticleController::class)->middleware('permission:writer,admin');
    Route::resource('pendingArticle', PendingArticleController::class)->middleware('permission:approve,admin');
});


// Route::group(['prefix' => 'user'], function () {
//     Route::post('login', [UserAuthController::class, 'login']);
//     Route::post('logout', [UserAuthController::class, 'logout']);
// });

// Route::group(['middleware' => ['api', 'checkPassword', 'changeLang', 'CheckUserToken:user-api'], 'namespace' => 'Api'], function () {
//     Route::get('offers', [CategoriesController::class, 'index']);
// });
