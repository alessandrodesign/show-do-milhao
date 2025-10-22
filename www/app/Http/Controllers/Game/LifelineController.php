<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\GameEngine;
use Illuminate\Http\Request;

class LifelineController extends Controller
{
    public function __construct(protected GameEngine $engine)
    {
    }

    public function use5050(Game $game, Request $request)
    {
        abort_if($game->user_id !== auth()->id(), 403);
        $data = $this->engine->use5050($game, (int)$request->input('step'));
        return response()->json($data);
    }

    public function useUniversitarios(Game $game, Request $request)
    {
        abort_if($game->user_id !== auth()->id(), 403);
        return response()->json($this->engine->useUniversitarios($game, (int)$request->input('step')));
    }

    public function usePlacas(Game $game, Request $request)
    {
        abort_if($game->user_id !== auth()->id(), 403);
        return response()->json($this->engine->usePlacas($game, (int)$request->input('step')));
    }

    public function usePulo(Game $game, Request $request)
    {
        abort_if($game->user_id !== auth()->id(), 403);
        $game = $this->engine->usePulo($game, (int)$request->input('step'));
        return response()->json(['current_step' => $game->current_step]);
    }
}

