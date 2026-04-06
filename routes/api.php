<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ArticleResolveController;

Route::apiResource('articles', ArticleController::class);

Route::apiResource('article-resolves', ArticleResolveController::class)->only(['store']);
