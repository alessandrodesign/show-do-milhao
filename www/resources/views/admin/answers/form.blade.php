@csrf

<div>
    <label class="block text-sm mb-1">Letra (A, B, C ou D)</label>
    <input type="text" name="label" maxlength="1" class="w-full p-2 rounded bg-slate-800 border border-slate-600"
           value="{{ old('label', $answer->label ?? '') }}" required>
    @error('label') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm mb-1">Texto da resposta</label>
    <textarea name="text" class="w-full p-2 rounded bg-slate-800 border border-slate-600" required>{{ old('text', $answer->text ?? '') }}</textarea>
    @error('text') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="is_correct" value="1" class="w-4 h-4 bg-slate-800"
        @checked(old('is_correct', $answer->is_correct ?? false))>
    <label>Marcar como correta</label>
</div>

<div class="flex gap-3 mt-6">
    <button class="bg-amber-400 text-black font-semibold px-4 py-2 rounded-xl hover:brightness-110">Salvar</button>
    <a href="{{ route('questions.answers.index', $question) }}" class="px-4 py-2 bg-gray-600 rounded-xl hover:bg-gray-700">Cancelar</a>
</div>
