<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DifficultyController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('difficulties', DifficultyController::class);
    Route::apiResource('questions', QuestionController::class);
});
