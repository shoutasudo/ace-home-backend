<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\TestController;
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

Route::get('/test', [TestController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/contact', [ContactController::class, 'store']);
Route::prefix('/admin')->group(function () {
    Route::prefix('/information')->group(function () {
        Route::get('/list', [InformationController::class, 'list']);
        Route::get('/store/uuid',[InformationController::class,'createUuid']);
        Route::post('/store/image',[InformationController::class,'contentImgStore']);
        Route::post('/filesExcept',[InformationController::class,'deleteFilesExcept']);
        Route::post('/store',[InformationController::class,'store']);
        Route::get('/edit/{registeredInfo}', [InformationController::class, 'edit']);
        Route::post('/update/{registeredInfo}', [InformationController::class, 'update']);
        Route::delete('/delete/{registeredInfo}', [InformationController::class, 'delete']);
    });
});
