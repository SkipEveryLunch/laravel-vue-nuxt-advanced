<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\AmbassadorController;
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
function common(String $scope){
        Route::post('register',[AuthController::class,'register']);
        Route::post('login',[AuthController::class,'login']);
        Route::middleware([$scope,'auth:sanctum'])->group(function(){
            Route::get('user',[AuthController::class,'user']);
            Route::delete('logout',[AuthController::class,'logout']);
            Route::put('updateInfo',[AuthController::class,'updateInfo']);
            Route::put('updatePassword',[AuthController::class,'updatePassword']);
        });  
}

Route::prefix('admin')->group(function(){
    common('scope.admin');
    Route::middleware(['scope.admin','auth:sanctum'])->group(function(){
        Route::resource('ambassadors', AmbassadorController::class);
        Route::resource('products', ProductController::class,['except'=>['index']]);
        Route::get('users/{id}/links',[LinkController::class,'index']);
        Route::get('orders',[OrderController::class,'index']);
        Route::apiResource('products', ProductController::class);
    });
});

Route::prefix('ambassador')->group(function(){
    common('scope.ambassador');
    Route::get('products/frontend',[ProductController::class,'frontend']);
    Route::get('products/backend',[ProductController::class,'backend']);
    Route::middleware(['scope.ambassador','auth:sanctum'])->group(function(){
        Route::get('stats',[StatsController::class,'index']);
        Route::get('ranking',[StatsController::class,'ranking']);
        Route::post('links',[LinkController::class,'store']);
    });
});

Route::prefix('checkout')->group(function(){
    Route::get('links/{code}',[LinkController::class,'show']);
    Route::post('orders',[OrderController::class,'store']);
    Route::post('orders/confirm',[OrderController::class,'confirm']);
});