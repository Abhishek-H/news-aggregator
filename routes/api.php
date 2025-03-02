<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'registerUser']);
Route::post('/login', [AuthController::class, 'loginUser']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logoutUser']);

    Route::get('/articles', [ArticleController::class, 'getAllArticles']);
    Route::get('/articles/{id}', [ArticleController::class, 'getArticle']);
    Route::post('/articles', [ArticleController::class, 'storeArticle']);

    Route::get('/preferences', [UserPreferenceController::class, 'getPreferences']);
    Route::post('/preferences', [UserPreferenceController::class, 'updatePreferences']);
    Route::get('/personalized-news', [UserPreferenceController::class, 'getPersonalizedNews']);
});
