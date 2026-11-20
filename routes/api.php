<?php

use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\UserCollectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FlipPlay REST API Routes
|--------------------------------------------------------------------------
*/

// 1. CRUD Media (index, store, show, update, destroy)
Route::apiResource('media', MediaController::class);

// 2. Custom Action: Koleksi Pengguna (Attach, Get Collection, Detach)
Route::prefix('users/{user}/collection')->group(function () {
    Route::get('/', [UserCollectionController::class, 'index'])->name('users.collection.index');
    Route::post('/', [UserCollectionController::class, 'store'])->name('users.collection.store');
    Route::delete('/{media}', [UserCollectionController::class, 'destroy'])->name('users.collection.destroy');
});