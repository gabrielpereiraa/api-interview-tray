<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'create']);

        Route::prefix('{user}')->group(function() {
            Route::get('/', [UserController::class, 'show']);
            Route::put('/', [UserController::class, 'update']);
            Route::delete('/', [UserController::class, 'destroy']); 

            Route::get('/sellers', [SellerController::class, 'indexByUser']);
            Route::get('/sales', [SaleController::class, 'indexByUser']);
        });
    });

    Route::prefix('sellers')->group(function() {
        Route::get('/', [SellerController::class, 'index']);
        Route::post('/', [SellerController::class, 'create']);

        Route::prefix('{seller}')->group(function() {
            Route::get('/', [SellerController::class, 'show']);
            Route::put('/', [SellerController::class, 'update']);
            Route::delete('/', [SellerController::class, 'destroy']); 

            Route::prefix('sales')->group(function () {
                Route::get('/', [SaleController::class, 'indexBySeller']);
                Route::post('/', [SaleController::class, 'create']);
                Route::put('/{sale}', [SaleController::class, 'update']);
            });
        });
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);

        Route::prefix('{sale}')->group(function () {
            Route::get('/', [SaleController::class, 'show']); 
            Route::delete('/', [SaleController::class, 'destroy']);
        });
    });
});


