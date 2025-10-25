<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameCategoryRequest;
use App\Http\Resources\GameCategoryResource;
use App\Models\GameCategory;
use Illuminate\Http\JsonResponse;

/**
 * CRUD de categorias do jogo.
 */
class GameCategoryController extends Controller
{
    public function index()
    {
        return GameCategoryResource::collection(GameCategory::all());
    }

    public function store(GameCategoryRequest $request): GameCategoryResource
    {
        $category = GameCategory::create($request->validated());

        return new GameCategoryResource($category);
    }

    public function show(GameCategory $gameCategory): GameCategoryResource
    {
        return new GameCategoryResource($gameCategory);
    }

    public function update(GameCategoryRequest $request, GameCategory $gameCategory): GameCategoryResource
    {
        $gameCategory->update($request->validated());

        return new GameCategoryResource($gameCategory);
    }

    public function destroy(GameCategory $gameCategory): JsonResponse
    {
        $gameCategory->delete();

        return response()->json(['deleted' => true]);
    }
}
