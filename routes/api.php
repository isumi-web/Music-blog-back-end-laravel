<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsAdmin;


// Authentication Routes
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Song Routes
Route::prefix('songs')->group(function () {
    Route::get('/', [SongController::class, 'index']);
    Route::get('/{song}', [SongController::class, 'show'])
        ->missing(fn() => response()->json(['message' => 'Song not found'], 404));
    Route::get('/albums/{albumId}', [SongController::class, 'byAlbum'])
        ->missing(fn() => response()->json(['message' => 'Album not found'], 404));
});

// Albums Routes
Route::prefix('albums')->group(function () {
    Route::get('/', [AlbumController::class, 'index']);
    Route::get('/{album}', [AlbumController::class, 'show'])
        ->missing(fn() => response()->json(['message' => 'Album not found'], 404));
});

Route::get('/public/artists', [RegisteredUserController::class, 'getPublicArtists']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::middleware(EnsureUserIsAdmin::class)->group(function () {
        Route::get('/user', [RegisteredUserController::class, 'index']);
        Route::delete('/user/{id}', [RegisteredUserController::class, 'destroy']);
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // Album Routes
    Route::prefix('albums')->group(function () {
        Route::post('/', [AlbumController::class, 'store']);
        Route::middleware(EnsureUserIsAdmin::class)->group(function () {
            Route::put('/{album}', [AlbumController::class, 'update']);
            Route::delete('/{album}', [AlbumController::class, 'destroy']);
        });
    });

    // Song Routes
    Route::prefix('songs')->group(function () {
        Route::post('/', [SongController::class, 'store']);
        Route::middleware(EnsureUserIsAdmin::class)->group(function () {
            Route::put('/{song}', [SongController::class, 'update']);
            Route::delete('/{song}', [SongController::class, 'destroy']);
        });
    });
});
