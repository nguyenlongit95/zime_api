<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[LoginController::class,'login']);
Route::post('/register',[RegisterController::class,'register']);

Route::middleware('auth:api')->name('api.')->group(function () {
    Route::get('/user/check-package', [UserController::class, 'checkPackage']) -> name('checkPackage');
    Route::get('/user/select-package', [UserController::class, 'selectPackage']) -> name('selectPackage');
    Route::get('/user/list-files', [UserController::class, 'listFiles']) -> name('listFiles');
    Route::post('user/upload-file', [UserController::class, 'uploadFile']) -> name('uploadFile');
    Route::get('/user/file-detail', [UserController::class, 'fileDetail']) -> name('fileDetail');
    Route::get('/user/file-delete', [UserController::class, 'deleteFile']) -> name('deleteFile');
});
