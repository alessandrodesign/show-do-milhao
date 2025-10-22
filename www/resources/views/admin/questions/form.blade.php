@csrf

<div>
    <label class="block text-sm mb-1">Título</label>
    <input type="text" name="title" class="w-full p-2 rounded bg-slate-800 border border-slate-600"
           value="{{ old('title', $question->title ?? '') }}" required>
    @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm mb-1">Enunciado</label>
    <textarea name="statement" class="w-full p-2 rounded bg-slate-800 border border-slate-600" required>{{ old('statement', $question->statement ?? '') }}</textarea>
    @error('statement') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm mb-1">Dificuldade</label>
    <select name="difficulty" class="w-full p-2 rounded bg-slate-800 border border-slate-600" required>
        <option value="">Selecione</option>
        @foreach (['easy'=>'Fácil','medium'=>'Média','hard'=>'Difícil','extreme'=>'Extrema'] as $k=>$v)
            <option value="{{ $k }}" @selected(old('difficulty', $question->difficulty ?? '') == $k)>{{ $v }}</option>
        @endforeach
    </select>
    @error('difficulty') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="active" value="1" class="w-4 h-4 bg-slate-800"
        @checked(old('active', $question->active ?? true))>
    <label>Ativar pergunta</label>
</div>

<div class="flex gap-3 mt-6">
    <button class="bg-amber-400 text-black font-semibold px-4 py-2 rounded-xl hover:brightness-110">Salvar</button>
    <a href="{{ route('questions.index') }}" class="px-4 py-2 bg-gray-600 rounded-xl hover:bg-gray-700">Cancelar</a>
</div>
