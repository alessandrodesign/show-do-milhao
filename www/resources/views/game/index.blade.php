@php
    use App\Services\GameEngine;
@endphp

@extends('layouts.app')

@section('content')
    <div
        x-data="gameScreen({{ json_encode([
        'game' => $game,
        'prizes' => GameEngine::PRIZES,
        'secureSteps' => GameEngine::SECURE_STEPS,
    ]) }})"
        class="grid grid-cols-12 gap-6"
    >

        {{-- Botão de início --}}
        <div class="col-span-12 flex justify-center mb-4" x-show="!state.started">
            <button
                @click="init()"
                class="px-6 py-3 bg-amber-500 text-black font-bold rounded-xl hover:brightness-110 transition-all">
                Iniciar
            </button>
        </div>

        <template x-if="state.started">
            <div class="col-span-12 grid grid-cols-12 gap-6">
                {{-- 🏆 Coluna de premiações --}}
                <aside class="col-span-12 lg:col-span-3 bg-white/5 rounded-2xl p-4">
                    <h3 class="font-bold mb-2">Prêmios</h3>
                    <ol class="space-y-1">
                        <template x-for="i in [...Array(16).keys()].map(n => 16 - n)">
                            <li
                                :class="{
                        'text-amber-400 font-bold': i === state.step,
                        'text-emerald-400': secureSteps.includes(i),
                        'text-white/80': i !== state.step && !secureSteps.includes(i)
                    }"
                            >
                                <span x-text="i"></span>. R$
                                <span x-text="prizes[i].toLocaleString('pt-BR')"></span>
                            </li>
                        </template>
                    </ol>
                </aside>

                {{-- 🧠 Coluna da pergunta --}}
                <section class="col-span-12 lg:col-span-6 space-y-4" x-show="state.started" x-cloak>

                    {{-- Fim de jogo --}}
                    <template x-if="state.over">
                        <div class="bg-black/40 rounded-2xl p-6 text-center animate-fade-in">
                            <h2 class="text-2xl font-extrabold mb-2" x-text="state.message"></h2>
                            <p>Prêmio: R$ <span x-text="state.current_prize.toLocaleString('pt-BR')"></span></p>
                            <a href="{{ route('home') }}"
                               class="mt-4 inline-block px-6 py-3 bg-amber-400 text-black rounded-2xl font-bold">
                                Voltar
                            </a>
                        </div>
                    </template>

                    {{-- Pergunta atual --}}
                    <template x-if="!state.over">
                        <div class="bg-white/5 rounded-2xl p-6 animate-fade-in">
                            <div class="text-sm text-white/70 mb-2">
                                Pergunta <span x-text="state.step"></span> de 16
                            </div>
                            <h2 class="text-xl font-bold mb-4" x-text="state.question.statement"></h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <template x-for="a in state.question.answers" :key="a.id">
                                    <button
                                        :disabled="state.lock"
                                        @click="selectAnswer($event, a)"
                                        class="text-left px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300"
                                        x-html="`<b>${a.label})</b> ${a.text}`">
                                    </button>
                                </template>
                            </div>

                            {{-- Ajuda --}}
                            <div class="mt-6 flex flex-wrap items-center gap-3">
                                <button
                                    :disabled="!lifelines['5050'] || state.lock"
                                    @click="use5050()"
                                    class="px-4 py-2 bg-indigo-500 rounded-xl disabled:opacity-40">
                                    50/50
                                </button>

                                <button
                                    :disabled="!lifelines.universitarios || state.lock"
                                    @click="useUniversitarios()"
                                    class="px-4 py-2 bg-blue-500 rounded-xl disabled:opacity-40">
                                    Universitários
                                </button>

                                <button
                                    :disabled="!lifelines.placas || state.lock"
                                    @click="usePlacas()"
                                    class="px-4 py-2 bg-teal-500 rounded-xl disabled:opacity-40">
                                    Placas
                                </button>

                                <button
                                    :disabled="lifelines.pulo <= 0 || state.lock"
                                    @click="usePulo()"
                                    class="px-4 py-2 bg-rose-500 rounded-xl disabled:opacity-40">
                                    Pular (<span x-text="lifelines.pulo"></span>)
                                </button>

                                <button @click="quitGame()" class="px-4 py-2 bg-red-700 hover:bg-red-800 rounded text-white">
                                    Encerrar Jogo
                                </button>
                            </div>
                        </div>
                    </template>
                </section>

                {{-- 💰 Coluna de status --}}
                <aside class="col-span-12 lg:col-span-3 bg-white/5 rounded-2xl p-4 space-y-1" x-show="state.started"
                       x-cloak>
                    <div>
                        Prêmio atual: <b>R$
                            <span x-text="state.current_prize.toLocaleString('pt-BR')"></span></b>
                    </div>
                    <div>
                        Garantido: <b>R$
                            <span x-text="state.secured_prize.toLocaleString('pt-BR')"></span></b>
                    </div>
                </aside>
            </div>
        </template>

        <!-- Modal/resumo de encerramento -->
        <template x-if="state.over && state.quitData">
            <div class="fixed inset-0 bg-black/80 flex items-center justify-center z-50">
                <div class="bg-white text-black rounded-lg p-6 max-w-sm w-full text-center">
                    <h2 class="text-2xl font-bold mb-2" x-text="state.quitData.message"></h2>
                    <p class="mb-1">🏆 Prêmio Atual: R$ <span x-text="state.quitData.current_prize"></span></p>
                    <p class="mb-4">💰 Prêmio Garantido: R$ <span x-text="state.quitData.secured_prize"></span></p>
                    <a href="/play" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 inline-block">
                        Iniciar Novo Jogo
                    </a>
                </div>
            </div>
        </template>
    </div>
@endsection
