<?php

use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\UserCollectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FlipPlay REST API Routes (Laravel 12)
|--------------------------------------------------------------------------
| Dapat diverifikasi melalui perintah: php artisan route:list
*/

// CRUD Standar Entitas Media (index, store, show, update, destroy)
Route::apiResource('media', MediaController::class)->parameters([
    'media' => 'media',
]);

// Endpoint Pengguna & Custom Action Relasi Many-to-Many (Koleksi Media)
Route::get('/users', [UserCollectionController::class, 'indexUsers'])
    ->name('users.index');

Route::get('/users/{user}/collections', [UserCollectionController::class, 'index'])
    ->name('users.collections.index');

Route::post('/users/{user}/collections', [UserCollectionController::class, 'store'])
    ->name('users.collections.store');

Route::delete('/users/{user}/collections/{media}', [UserCollectionController::class, 'destroy'])
    ->name('users.collections.destroy');
