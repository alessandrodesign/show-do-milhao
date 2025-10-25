<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    GameCategoryController,
    GameDifficultyController,
    GameQuestionController
};
use App\Http\Controllers\{
    GameController,
    RankingController
};
use App\Http\Controllers\GameStatsController;

/*
|--------------------------------------------------------------------------
| API Routes - Show do Milhão
|--------------------------------------------------------------------------
| Estrutura organizada para as rotas do jogo e do painel administrativo.
| As rotas administrativas são protegidas via Sanctum.
| As rotas do jogo e ranking são públicas.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Painel Administrativo (protegido por Sanctum)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    // 📁 Categorias
    Route::get('categories', [GameCategoryController::class, 'index']);
    Route::post('categories', [GameCategoryController::class, 'store']);
    Route::get('categories/{gameCategory}', [GameCategoryController::class, 'show']);
    Route::put('categories/{gameCategory}', [GameCategoryController::class, 'update']);
    Route::delete('categories/{gameCategory}', [GameCategoryController::class, 'destroy']);

    // 🧩 Dificuldades
    Route::get('difficulties', [GameDifficultyController::class, 'index']);
    Route::post('difficulties', [GameDifficultyController::class, 'store']);
    Route::get('difficulties/{gameDifficulty}', [GameDifficultyController::class, 'show']);
    Route::put('difficulties/{gameDifficulty}', [GameDifficultyController::class, 'update']);
    Route::delete('difficulties/{gameDifficulty}', [GameDifficultyController::class, 'destroy']);

    // ❓ Perguntas e respostas
    Route::get('questions', [GameQuestionController::class, 'index']);
    Route::post('questions', [GameQuestionController::class, 'store']);
    Route::get('questions/{gameQuestion}', [GameQuestionController::class, 'show']);
    Route::put('questions/{gameQuestion}', [GameQuestionController::class, 'update']);
    Route::delete('questions/{gameQuestion}', [GameQuestionController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Rotas Públicas do Jogo (GameController)
|--------------------------------------------------------------------------
*/
Route::prefix('game')->group(function () {
    // 🕹️ Lista categorias
    Route::get('categories', [GameController::class, 'categories']);

    // 🕹️ Iniciar uma nova partida
    Route::post('start', [GameController::class, 'start']);

    // 📊 Obter estado atual do jogo
    Route::get('{game}/current', [GameController::class, 'current']);

    // 🎯 Jogador seleciona resposta
    Route::post('{game}/select-answer', [GameController::class, 'selectAnswer']);

    // 🔍 Revelar resultado (acerto/erro)
    Route::post('{game}/reveal', [GameController::class, 'reveal']);

    // ⏭️ Continuar para próxima pergunta
    Route::post('{game}/continue', [GameController::class, 'continue']);

    // 🛑 Parar o jogo e levar prêmio atual
    Route::post('{game}/stop', [GameController::class, 'stop']);

    // 🆘 Ajudas (Lifelines)
    Route::post('{game}/lifeline/{type}/confirm', [GameController::class, 'useLifeline']);
});

/*
|--------------------------------------------------------------------------
| Estatísticas e Rankings
|--------------------------------------------------------------------------
*/
Route::prefix('stats')->group(function () {
    Route::get('global', [GameStatsController::class, 'global']);
    Route::get('player/{name}', [GameStatsController::class, 'player']);
});

Route::get('ranking/top', [RankingController::class, 'top']);
Route::get('ranking/detailed', [RankingController::class, 'detailed']);
