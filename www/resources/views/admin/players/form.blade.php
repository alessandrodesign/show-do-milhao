@csrf

<div>
    <label class="block text-sm mb-1">Apelido</label>
    <input type="text" name="nickname" class="w-full p-2 rounded bg-slate-800 border border-slate-600"
           value="{{ old('nickname', $player->nickname ?? '') }}">
    @error('nickname') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm mb-1">Score</label>
    <input type="number" name="score" class="w-full p-2 rounded bg-slate-800 border border-slate-600"
           value="{{ old('score', $player->score ?? 0) }}">
    @error('score') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm mb-1">Melhor Prêmio (R$)</label>
    <input type="number" name="best_prize" class="w-full p-2 rounded bg-slate-800 border border-slate-600"
           value="{{ old('best_prize', $player->best_prize ?? 0) }}">
    @error('best_prize') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex gap-3 mt-6">
    <button class="bg-amber-400 text-black font-semibold px-4 py-2 rounded-xl hover:brightness-110">Salvar</button>
    <a href="{{ route('players.index') }}" class="px-4 py-2 bg-gray-600 rounded-xl hover:bg-gray-700">Cancelar</a>
</div>
