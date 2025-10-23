<?php

use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Api\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::middleware('admin.guard')->prefix('admin')->group(function () {
    Route::resource('questions', QuestionController::class);
    Route::post('/game/start', [GameController::class, 'start']);
    Route::post('/game/question', [GameController::class, 'question']);
    Route::post('/game/answer', [GameController::class, 'answer']);
    Route::get('/game/ranking', [GameController::class, 'ranking']);
    Route::post('/game/help/universitarios', [GameController::class, 'helpUniversitarios']);
});
