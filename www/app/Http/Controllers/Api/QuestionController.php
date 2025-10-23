<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class QuestionController extends Controller
{
    public function index()
    {
        return Question::with(['category', 'difficulty', 'alternatives'])
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'category_id'   => 'required|exists:categories,id',
                'difficulty_id' => 'required|exists:difficulties,id',
                'statement'     => 'required|string',
                'alternatives'  => 'required|array|min:2',
                'correct'       => 'required|string|max:1',
            ]);

            return DB::transaction(function () use ($data) {
                $question = Question::create($data);
                foreach ($data['alternatives'] as $letter => $text) {
                    Alternative::create([
                        'question_id' => $question->id,
                        'letter'      => $letter,
                        'text'        => $text,
                        'is_correct'  => ($letter === $data['correct']),
                    ]);
                }

                return $question->load(['category', 'difficulty', 'alternatives']);
            });
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(Question $question)
    {
        return $question->load(['category', 'difficulty', 'alternatives']);
    }

    public function update(Request $request, Question $question)
    {
        try {
            $data = $request->validate([
                'statement'    => 'required|string',
                'alternatives' => 'nullable|array|min:2',
                'correct'      => 'nullable|string|max:1',
            ]);

            DB::transaction(function () use ($question, $data) {
                $question->update($data);
                if (!empty($data['alternatives'])) {
                    $question->alternatives()->delete();
                    foreach ($data['alternatives'] as $letter => $text) {
                        Alternative::create([
                            'question_id' => $question->id,
                            'letter'      => $letter,
                            'text'        => $text,
                            'is_correct'  => ($letter === ($data['correct'] ?? '')),
                        ]);
                    }
                }
            });

            return $question->load(['category', 'difficulty', 'alternatives']);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return response()->noContent();
    }
}
