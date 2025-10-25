<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameDifficultyRequest;
use App\Http\Resources\GameDifficultyResource;
use App\Models\GameDifficulty;
use Illuminate\Http\JsonResponse;

/**
 * CRUD de níveis de dificuldade.
 */
class GameDifficultyController extends Controller
{
    public function index()
    {
        return GameDifficultyResource::collection(GameDifficulty::orderBy('order')->get());
    }

    public function store(GameDifficultyRequest $request): GameDifficultyResource
    {
        $difficulty = GameDifficulty::create($request->validated());

        return new GameDifficultyResource($difficulty);
    }

    public function show(GameDifficulty $gameDifficulty): GameDifficultyResource
    {
        return new GameDifficultyResource($gameDifficulty);
    }

    public function update(GameDifficultyRequest $request, GameDifficulty $gameDifficulty): GameDifficultyResource
    {
        $gameDifficulty->update($request->validated());

        return new GameDifficultyResource($gameDifficulty);
    }

    public function destroy(GameDifficulty $gameDifficulty): JsonResponse
    {
        $gameDifficulty->delete();

        return response()->json(['deleted' => true]);
    }
}
