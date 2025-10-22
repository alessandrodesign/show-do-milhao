@extends('layouts.app')

@section('content')
    <div class="text-center space-y-8 max-w-4xl mx-auto" x-data>
        <!-- Título Principal -->
        <h1 class="text-5xl font-extrabold tracking-wide text-amber-400 drop-shadow-lg">
            SHOW DO MILHÃO
        </h1>

        <!-- Ranking -->
        <div class="bg-white/5 rounded-2xl p-6 shadow-lg backdrop-blur">
            <h2 class="text-2xl font-semibold mb-4 text-white flex items-center justify-center gap-2">
                🏆 Top Jogadores
            </h2>

            <ul class="divide-y divide-white/10 text-white text-base">
                @forelse($leaders as $p)
                    <li class="py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <span class="font-semibold text-amber-300">
                            {{ $p->nickname ?? 'Jogador' }}
                        </span>
                        <span class="text-sm sm:text-base opacity-90">
                            Melhor Prêmio:
                            <strong class="text-green-400">R$ {{ number_format($p->best_prize, 0, ',', '.') }}</strong>
                            • Score:
                            <strong>{{ $p->score }}</strong>
                        </span>
                    </li>
                @empty
                    <li class="py-3 text-gray-300">
                        Ainda não há rankings registrados.
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Botão de ação -->
        @auth
            <form method="GET" action="{{ route('game.index') }}" x-data>
                @csrf
                <button type="submit"
                        class="px-8 py-3 rounded-2xl bg-amber-400 text-black font-bold text-lg shadow-lg transition transform hover:scale-105 hover:brightness-110"
                        x-on:mouseenter="getSfxComponent()?.pulse($el)"
                        x-on:click="getSfxComponent()?.playClick()">
                    🎮 Iniciar Novo Jogo
                </button>
            </form>
        @else
            <a href="{{ route('login') }}"
               class="px-8 py-3 rounded-2xl bg-amber-400 text-black font-bold text-lg shadow-lg transition transform hover:scale-105 hover:brightness-110"
               x-on:mouseenter="getSfxComponent()?.pulse($el)"
               x-on:click="getSfxComponent()?.playClick()">
                Entrar
            </a>
        @endauth
    </div>
@endsection
