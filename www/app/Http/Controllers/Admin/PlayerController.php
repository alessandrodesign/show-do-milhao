<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerUpdateRequest;
use App\Models\User;

class PlayerController extends Controller
{
    public function index()
    {
        $players = User::role('player')->orderByDesc('best_prize')->paginate(20);
        return view('admin.players.index', compact('players'));
    }

    public function edit(User $player)
    {
        $this->authorize('update', $player);
        return view('admin.players.edit', compact('player'));
    }

    public function update(PlayerUpdateRequest $request, User $player)
    {
        $player->update($request->validated());
        return redirect()->route('players.index')->with('success', 'Jogador atualizado com sucesso!');
    }

    public function destroy(User $player)
    {
        $this->authorize('delete', $player);
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Jogador removido!');
    }
}
