<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameQuestionRequest;
use App\Http\Resources\GameQuestionResource;
use App\Models\GameQuestion;
use Illuminate\Http\JsonResponse;

/**
 * CRUD de perguntas com respostas.
 */
class GameQuestionController extends Controller
{
    public function index()
    {
        return GameQuestionResource::collection(
            GameQuestion::with(['answers', 'category', 'difficulty'])->latest()->get()
        );
    }

    public function store(GameQuestionRequest $request): GameQuestionResource
    {
        $validated = $request->validated();

        $question = GameQuestion::create([
            'category_id'   => $validated['category_id'],
            'difficulty_id' => $validated['difficulty_id'],
            'question'      => $validated['question'],
            'hint'          => $validated['hint'] ?? null,
        ]);

        foreach ($validated['answers'] as $answerData) {
            $question->answers()->create($answerData);
        }

        return new GameQuestionResource($question->load(['answers', 'category', 'difficulty']));
    }

    public function show(GameQuestion $gameQuestion): GameQuestionResource
    {
        return new GameQuestionResource($gameQuestion->load(['answers', 'category', 'difficulty']));
    }

    public function update(GameQuestionRequest $request, GameQuestion $gameQuestion): GameQuestionResource
    {
        $validated = $request->validated();
        $gameQuestion->update([
            'category_id'   => $validated['category_id'],
            'difficulty_id' => $validated['difficulty_id'],
            'question'      => $validated['question'],
            'hint'          => $validated['hint'] ?? null,
        ]);

        // Remove respostas antigas e recria
        $gameQuestion->answers()->delete();
        foreach ($validated['answers'] as $answerData) {
            $gameQuestion->answers()->create($answerData);
        }

        return new GameQuestionResource($gameQuestion->load(['answers', 'category', 'difficulty']));
    }

    public function destroy(GameQuestion $gameQuestion): JsonResponse
    {
        $gameQuestion->answers()->delete();
        $gameQuestion->delete();

        return response()->json(['deleted' => true]);
    }
}
