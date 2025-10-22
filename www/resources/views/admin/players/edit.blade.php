@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">✏️ Editar Jogador</h1>

    <form action="{{ route('players.update', $player) }}" method="POST" class="space-y-4 max-w-xl">
        @method('PUT')
        @include('admin.players.form', ['player'=>$player])
    </form>
@endsection
