@extends('layouts.app')

@section('content')
    <div class="relative min-h-[80vh] flex flex-col items-center justify-center text-center space-y-10"
         x-data
         x-init="welcome()">

        {{-- Componente de sons --}}
        <div id="sfx" x-data="sfxInit()" class="hidden"></div>

        <!-- Fundo com gradiente e brilho -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a]"></div>
        <div class="absolute inset-0 bg-[url('/images/particles.svg')] opacity-10"></div>

        <!-- Título Principal -->
        <div class="relative">
            <h1 class="text-6xl md:text-7xl font-extrabold tracking-wide text-amber-400 drop-shadow-[0_0_15px_rgba(251,191,36,0.7)] animate-pulse">
                SHOW DO MILHÃO
            </h1>
            <p class="mt-2 text-white/60 text-sm md:text-base">🏆 Venha testar seus conhecimentos e ganhar prêmios
                virtuais!</p>
        </div>

        <!-- Ranking -->
        <div
            class="relative bg-white/5 rounded-2xl p-6 shadow-lg backdrop-blur max-w-3xl w-full border border-white/10">
            <h2 class="text-2xl font-semibold mb-4 text-white flex items-center justify-center gap-2">
                🏆 Top Jogadores
            </h2>

            <ul class="divide-y divide-white/10 text-white text-base">
                @forelse($leaders as $p)
                    <li class="rank-item opacity-0 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <span class="font-semibold text-amber-300 flex items-center gap-2">
                            <span class="text-lg">👤</span> {{ $p->nickname ?? 'Jogador' }}
                        </span>
                        <span class="text-sm sm:text-base opacity-90">
                            💰 Melhor Prêmio:
                            <strong class="text-green-400">R$ {{ number_format($p->best_prize, 0, ',', '.') }}</strong>
                            • 🧠 Score:
                            <strong>{{ $p->score }}</strong>
                        </span>
                    </li>
                @empty
                    <li class="py-3 text-gray-300 text-lg">
                        Ainda não há rankings registrados.
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Botão de ação -->
        <div class="relative">
            @auth
                <form method="GET" action="{{ route('game.index') }}">
                    @csrf
                    <button type="submit"
                            class="group relative px-10 py-4 rounded-2xl bg-gradient-to-r from-amber-400 to-yellow-500 text-black font-extrabold text-lg shadow-lg transition-all transform hover:scale-105 hover:brightness-110 overflow-hidden"
                            x-on:mouseenter="getSfxComponent()?.pulse($el)"
                            x-on:click="getSfxComponent()?.playClick()">
                        <span class="relative z-10 flex items-center gap-2">
                            🎮 Iniciar Novo Jogo
                        </span>
                        <div class="absolute inset-0 bg-white opacity-20 group-hover:opacity-0 transition-all"></div>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="group relative px-10 py-4 rounded-2xl bg-gradient-to-r from-amber-400 to-yellow-500 text-black font-extrabold text-lg shadow-lg transition-all transform hover:scale-105 hover:brightness-110 overflow-hidden"
                   x-on:mouseenter="getSfxComponent()?.pulse($el)"
                   x-on:click="getSfxComponent()?.playClick()">
                    <span class="relative z-10 flex items-center gap-2">
                        🔐 Entrar
                    </span>
                    <div class="absolute inset-0 bg-white opacity-20 group-hover:opacity-0 transition-all"></div>
                </a>
            @endauth
        </div>
    </div>
@endsection
