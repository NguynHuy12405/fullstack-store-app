<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\VariantController as AdminVariantController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\ProductController as ApiProductController;

Route::prefix('products')->group(function () {
    // list
    Route::get('/', [ApiProductController::class, 'index']);
    // search         
    Route::get('/search', [ApiProductController::class, 'search']);   
    // By Cate, Brand
    Route::get('/category/{slug}', [ApiProductController::class, 'byCategory']);
    Route::get('/brand/{slug}', [ApiProductController::class, 'byBrand']);
    // detail
    Route::get('/{slug}', [ApiProductController::class, 'show']);
});

Route::prefix('admin/products')->group(function () {

    Route::get('/', [AdminProductController::class, 'index']);
    Route::post('/', [AdminProductController::class, 'store']);

    Route::get('/{id}', [AdminProductController::class, 'edit']);
    Route::put('/{id}', [AdminProductController::class, 'update']);
    Route::delete('/{id}', [AdminProductController::class, 'destroy']);
});

Route::prefix('admin/variants')->group(function () {

    Route::post('/', [AdminVariantController::class, 'store']);
    Route::put('/{id}', [AdminVariantController::class, 'update']);
    Route::delete('/{id}', [AdminVariantController::class, 'destroy']);
});

