<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DifficultyController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('difficulties', DifficultyController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::middleware('throttle.game:20,1')->prefix('game')->group(function () {
        Route::post('start', [GameController::class, 'start']);
        Route::post('question', [GameController::class, 'question']);
        Route::post('answer', [GameController::class, 'answer']);
        Route::post('end', [GameController::class, 'end']);
        Route::get('ranking', [GameController::class, 'ranking']);
        Route::post('help/universitarios', [GameController::class, 'helpUniversitarios']);
        Route::get('{gameId}/stats', [GameController::class, 'stats']);
    });
});
