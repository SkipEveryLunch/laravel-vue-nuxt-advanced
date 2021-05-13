<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\OrderController;
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
        Route::resource('products', ProductController::class);
        Route::get('users/{id}/links',[LinkController::class,'index']);
        Route::get('orders',[OrderController::class,'index']);
    });
});

Route::prefix('ambassador')->group(function(){
    common('scope.ambassador');
});
