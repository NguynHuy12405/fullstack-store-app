<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\VariantController as AdminVariantController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\AuthController;

// Api products
Route::prefix('products')->group(function () {

    // GET /api/products
    Route::get('/', [ApiProductController::class, 'index']);

    // Filter
    Route::get('categories/{slug}/products', [ApiProductController::class, 'byCategory']);
    Route::get('brands/{slug}/products', [ApiProductController::class, 'byBrand']);

    // GET /api/products/ao-so-mi-lua
    Route::get('{slug}', [ApiProductController::class, 'show']);
    
    // admin
    Route::get('/', [ApiProductController::class, 'adminIndex']);
    Route::post('/', [ApiProductController::class, 'store']);
    Route::get('{id}', [ApiProductController::class, 'edit']);
    Route::put('{id}', [ApiProductController::class, 'update']);
    Route::delete('{id}', [ApiProductController::class, 'destroy']);

    // variant
    Route::post('/', [AdminVariantController::class, 'store']);
    Route::put('{id}', [AdminVariantController::class, 'update']);
    Route::delete('{id}', [AdminVariantController::class, 'destroy']);
});
