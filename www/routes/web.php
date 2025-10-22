<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{QuestionController,AnswerController,PlayerController};
use App\Http\Controllers\Game\{GameController, LifelineController, LeaderboardController};

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

Route::get('/', [LeaderboardController::class,'index'])->name('home');

// Auth (Breeze)
require __DIR__.'/auth.php';

// JOGO
Route::middleware(['auth'])->group(function () {
    Route::get('/play/{game?}', [GameController::class, 'index'])->name('game.index');
    Route::post('/play/create', [GameController::class, 'create'])->name('game.create');
    Route::get('/play/{game}/questions', [GameController::class, 'getQuestions'])->name('game.questions');
    Route::post('/play/{game}/answer', [GameController::class, 'answer'])->name('game.answer');
    Route::post('/play/{game}/lifeline/{type}', [GameController::class, 'lifeline'])->name('game.lifeline');
    Route::post('/play/{game}/quit', [GameController::class, 'quit'])->name('game.quit');
});

// ADMIN
Route::prefix('/admin')->middleware(['auth','role:admin'])->group(function(){
    Route::resource('questions', QuestionController::class);
    Route::resource('questions.answers', AnswerController::class)->shallow();
    Route::resource('players', PlayerController::class)->only(['index','edit','update','destroy']);
});

