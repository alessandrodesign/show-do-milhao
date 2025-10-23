<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with(['category', 'difficulty'])->paginate(10);

        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $categories   = Category::all();
        $difficulties = Difficulty::all();

        return view('admin.questions.create', compact('categories', 'difficulties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'difficulty_id' => 'required|exists:difficulties,id',
            'statement'     => 'required|string',
            'alternatives'  => 'required|array|min:2',
            'correct'       => 'required|string',
        ]);

        DB::transaction(function () use ($data) {
            $question = Question::create($data);
            foreach ($data['alternatives'] as $letter => $text) {
                Alternative::create([
                    'question_id' => $question->id,
                    'letter'      => $letter,
                    'text'        => $text,
                    'is_correct'  => ($letter === $data['correct']),
                ]);
            }
        });

        return redirect()->route('admin.questions.index')->with('success', 'Pergunta criada!');
    }
}
