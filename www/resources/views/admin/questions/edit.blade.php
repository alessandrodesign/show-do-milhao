@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">✏️ Editar Pergunta</h1>

    <form action="{{ route('questions.update', $question) }}" method="POST" class="space-y-4 max-w-xl">
        @method('PUT')
        @include('admin.questions.form', ['question'=>$question])
    </form>
@endsection
