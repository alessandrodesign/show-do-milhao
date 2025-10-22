<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Models\Answer;
use App\Models\Question;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Answer::class, 'answer');
    }

    public function index(Question $question)
    {
        $answers = $question->answers;
        return view('admin.answers.index', compact('question', 'answers'));
    }

    public function create(Question $question)
    {
        return view('admin.answers.create', compact('question'));
    }

    public function store(StoreAnswerRequest $request, Question $question)
    {
        $question->answers()->create($request->validated());

        return redirect()
            ->route('questions.answers.index', $question)
            ->with('success', 'Resposta criada com sucesso!');
    }

    public function edit(Question $question, Answer $answer)
    {
        return view('admin.answers.edit', compact('question', 'answer'));
    }

    public function update(UpdateAnswerRequest $request, Question $question, Answer $answer)
    {
        $answer->update($request->validated());

        return redirect()
            ->route('questions.answers.index', $question)
            ->with('success', 'Resposta atualizada com sucesso!');
    }

    public function destroy(Question $question, Answer $answer)
    {
        $answer->delete();
        return redirect()->route('questions.answers.index', $question)->with('success', 'Resposta removida!');
    }
}
