<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaders = User::whereHas('roles', fn($q) => $q->where('name', 'player'))
            ->orderByDesc('best_prize')->orderByDesc('score')->limit(10)->get(['nickname', 'best_prize', 'score']);
        return view('welcome', compact('leaders'));
    }
}

