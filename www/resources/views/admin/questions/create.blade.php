@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">➕ Nova Pergunta</h1>

    <form action="{{ route('questions.store') }}" method="POST" class="space-y-4 max-w-xl">
        @include('admin.questions.form')
    </form>
@endsection
