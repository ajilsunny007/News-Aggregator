<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\UserPreferenceController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Articles
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/search', [ArticleController::class, 'search']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::get('/feed', [ArticleController::class, 'personalizedFeed']);

    // User preferences
    Route::get('/preferences', [UserPreferenceController::class, 'show']);
    Route::put('/preferences', [UserPreferenceController::class, 'update']);
});
