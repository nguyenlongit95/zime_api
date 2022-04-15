<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\UserController;

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

Route::get('/login',[LoginController::class,'index']) -> name('login');
Route::post('/login/authenticate',[LoginController::class,'login']) -> name('authenticate');
Route::get('logout',[LogoutController::class,'logout']) -> name('logout');

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
        Route::get('/detail/{id}',[UserController::class,'detail'])->name('detail');
        Route::get('/edit/{id}',[UserController::class,'edit'])->name('edit');
        Route::post('/edit/{id}',[UserController::class,'update'])->name('edit.update');
        Route::delete('/delete',[UserController::class,'delete'])->name('delete');
        Route::get('/show-file',[UserController::class,'showFile'])->name('showFile');
    });
});
