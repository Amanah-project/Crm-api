<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ArticleResolveController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;

Route::post('login', [AuthController::class, 'store'])->name('login');
Route::delete('login', [AuthController::class, 'destroy'])->middleware('auth:sanctum');

Route::apiResource('register', RegisterController::class)->only(['store']);
Route::apiResource('articles', ArticleController::class);
Route::apiResource('article-resolves', ArticleResolveController::class)->only(['store']);
