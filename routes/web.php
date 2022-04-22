<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\UserController as ClientController;
use App\Http\Controllers\Client\LoginController as ClientLoginController;
use App\Http\Controllers\Client\LogoutController as ClientLogoutController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/',[ClientController::class,'index']);
Route::get('/login',[ClientLoginController::class,'index']) -> name('client.login');
Route::post('/login/authenticate',[ClientLoginController::class,'login']) -> name('client.authenticate');
Route::get('/logout',[ClientLogoutController::class,'logout']) ;
Route::get('/user/list-files', [ClientController::class, 'listFiles']) -> name('listFiles');
Route::get('/user/file-detail/{id}', [ClientController::class, 'fileDetail']) -> name('fileDetail');
Route::get('/user/file-delete/{id}', [ClientController::class, 'deleteFile']) -> name('deleteFile');
Route::post('/user/upload-file', [ClientController::class, 'uploadFile']) -> name('uploadFile');


Route::get('/admin/login',[LoginController::class,'index']) -> name('login');
Route::post('/admin/login/authenticate',[LoginController::class,'login']) -> name('authenticate');
Route::get('/admin/logout',[LogoutController::class,'logout']) -> name('logout');
Route::prefix('/admin')->name('admin.')->middleware('auth')->group(function (){
    Route::get('/dashboard',[DashboardController::class,'index']) -> name('dashboard');
    Route::prefix('package')->name('package.')->group(function (){
        Route::get('',[PackageController::class,'index'])->name('index');
        Route::get('/create',[PackageController::class,'create'])->name('create');
        Route::post('/create',[PackageController::class,'store'])->name('store');
        Route::get('/edit/{id}',[PackageController::class,'edit'])->name('edit');
        Route::post('/edit/{id}',[PackageController::class,'update'])->name('edit.update');
        Route::get('/delete',[PackageController::class,'delete'])->name('delete');
    });
    Route::prefix('user')->name('user.')->group(function (){
        Route::get('',[UserController::class,'index'])->name('index');
        Route::get('/search',[UserController::class,'search'])->name('search');
        Route::get('/detail/{id}',[UserController::class,'detail'])->name('detail');
        Route::get('/edit/{id}',[UserController::class,'edit'])->name('edit');
        Route::post('/edit/{id}',[UserController::class,'update'])->name('edit.update');
        Route::delete('/delete',[UserController::class,'delete'])->name('delete');
        Route::get('/show-file',[UserController::class,'showFile'])->name('showFile');
    });
    Route::prefix('dashboard')->name('dashboard.')->group(function(){
        Route::get('/donut-chart',[DashboardController::class,'donutChart'])->name('donutChart');
        Route::get('/area-chart',[DashboardController::class,'areaChart'])->name('areaChart');
        Route::get('/line-chart',[DashboardController::class,'lineChart'])->name('lineChart');
    });

});
