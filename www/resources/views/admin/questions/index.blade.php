@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Perguntas</h2>
        <a href="{{ route('questions.create') }}" class="btn btn-primary mb-3">Nova Pergunta</a>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th><th>Enunciado</th><th>Categoria</th><th>Dificuldade</th><th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($questions as $q)
                <tr>
                    <td>{{ $q->id }}</td>
                    <td>{{ Str::limit($q->statement, 50) }}</td>
                    <td>{{ $q->category->name }}</td>
                    <td>{{ $q->difficulty->name }}</td>
                    <td><a href="#" class="btn btn-sm btn-info">Editar</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $questions->links() }}
    </div>
@endsection
