@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">➕ Nova Resposta</h1>

    <form action="{{ route('questions.answers.store', $question) }}" method="POST" class="space-y-4 max-w-xl">
        @include('admin.answers.form')
    </form>
@endsection
