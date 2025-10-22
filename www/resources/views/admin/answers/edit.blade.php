@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">✏️ Editar Resposta</h1>

    <form action="{{ route('answers.update', $answer) }}" method="POST" class="space-y-4 max-w-xl">
        @method('PUT')
        @include('admin.answers.form', ['answer'=>$answer])
    </form>
@endsection
