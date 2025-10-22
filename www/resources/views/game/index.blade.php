@extends('layouts.app')

@section('content')
    @php
        /** @var array $payload */
        $payloadJson = json_encode($payload ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    @endphp

    <div class="max-w-6xl mx-auto" x-data="gameScreen({!! $payloadJson !!})" x-init="init()">
        {{-- Sons --}}
        <div id="sfx" x-data="sfxInit()" class="hidden"></div>

        {{-- HUD --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between mb-6">
            <div class="space-y-1">
                <h1 class="text-3xl font-extrabold text-amber-400 tracking-wide">Show do Milhão</h1>
                <p class="text-white/70 text-sm">Etapa <b x-text="state.step"></b></p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button"
                        class="px-3 py-2 rounded-xl bg-amber-400 text-black font-bold hover:brightness-110"
                        @mouseenter="getSfxComponent()?.pulse($el)"
                        @click="playClick(); quitGame()">
                    Encerrar
                </button>
                <button type="button"
                        class="px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-white"
                        @mouseenter="getSfxComponent()?.pulse($el)"
                        @click="toggleMusic()">
                    🎵 Música
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Pergunta + Respostas --}}
            <div class="lg:col-span-2 space-y-4">
                <div id="question-card" class="bg-white/5 rounded-2xl p-5 border border-white/10 shadow-xl">
                    <div id="question-area" class="min-h-[160px]">
                        <template x-if="!state.question?.text">
                            <div class="animate-pulse h-24 bg-white/10 rounded-xl"></div>
                        </template>
                        <template x-if="state.question?.text">
                            <div>
                                <h6 class="font-semibold text-white mb-2">
                                    <span class="opacity-70 mr-2">❓</span>
                                    <span x-text="state.question.text"></span>
                                </h6>
                                <h2 class="text-xl font-bold mb-4" x-text="state.question.statement"></h2>

                                <div class="grid sm:grid-cols-2 gap-3 mt-4">
                                    <template x-for="a in (state.question.answers || [])" :key="a.id">
                                        <button type="button"
                                                class="text-left px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300"
                                                :disabled="state.lock || state.evaluating"
                                                :data-answer="true"
                                                :data-answer-id="a.id"
                                                @mouseenter="getSfxComponent()?.pulse($el)"
                                                @click="playClick(); selectAnswer($event, a)">
                                            <span class="font-bold mr-1" x-text="(a.label || '').toString().toUpperCase() + ')'"></span>
                                            <span x-html="a.text"></span>
                                            <span class="float-right text-cyan-300 font-semibold"
                                                  x-show="a.hintPercent !== undefined"
                                                  x-text="`(${a.hintPercent}%)`"
                                                  data-answer-percent></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Ajudas --}}
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white border border-white/10"
                            data-lifeline="5050"
                            :disabled="!lifelines['5050'] || state.lock || state.evaluating"
                            @mouseenter="getSfxComponent()?.pulse($el)"
                            @click="playClick(); useLifeline('5050')">
                        50:50
                    </button>
                    <button type="button" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white border border-white/10"
                            data-lifeline="universitarios"
                            :disabled="!lifelines.universitarios || state.lock || state.evaluating"
                            @mouseenter="getSfxComponent()?.pulse($el)"
                            @click="playClick(); useLifeline('universitarios')">
                        Universitários
                    </button>
                    <button type="button" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white border border-white/10"
                            data-lifeline="placas"
                            :disabled="!lifelines.placas || state.lock || state.evaluating"
                            @mouseenter="getSfxComponent()?.pulse($el)"
                            @click="playClick(); useLifeline('placas')">
                        Placas
                    </button>
                    <button type="button" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-black font-bold border border-white/10"
                            :disabled="!lifelines.pulo || state.lock || state.evaluating"
                            @mouseenter="getSfxComponent()?.pulse($el)"
                            @click="playClick(); usePulo()">
                        Pular (<span x-text="lifelines.pulo"></span>)
                    </button>
                </div>
            </div>

            {{-- Escada de prêmios --}}
            <aside class="bg-white/5 rounded-2xl p-4 border border-white/10 shadow-xl">
                <h3 class="text-lg font-semibold text-white/90 mb-3">📈 Escada de Prêmios</h3>
                <ul class="space-y-1 text-sm">
                    <template x-for="p in prizes" :key="p.step">
                        <li class="flex items-center justify-between px-3 py-2 rounded-lg"
                            :data-prize-step="p.step"
                            :class="{ 'bg-amber-500/20 text-amber-200 font-semibold': p.step === state.step, 'bg-white/10 text-white/80': p.step !== state.step }">
                            <span x-text="`Etapa ${p.step}`"></span>
                            <span x-text="`R$ ${Number(p.value).toLocaleString('pt-BR')}`"></span>
                        </li>
                    </template>
                </ul>

                <div class="mt-4 text-white/80 text-sm space-y-1">
                    <div>💰 Atual: <b x-text="`R$ ${Number(state.current_prize).toLocaleString('pt-BR')}`"></b></div>
                    <div>🛡️ Garantido: <b x-text="`R$ ${Number(state.secured_prize).toLocaleString('pt-BR')}`"></b></div>
                </div>
            </aside>
        </div>

        {{-- Loading --}}
        <template x-if="state.loading">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full border-4 border-amber-400 border-t-transparent animate-spin mx-auto mb-4"></div>
                    <p class="text-white/90 font-semibold text-lg">Consultando...</p>
                    <p class="text-white/60 text-sm">Segure a emoção! 🎬</p>
                </div>
            </div>
        </template>

        {{-- Modal fim de jogo --}}
        <template x-if="state.over && state.quitData">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80">
                <div class="bg-white text-black rounded-2xl p-6 w-[92%] max-w-md text-center shadow-2xl">
                    <h2 class="text-2xl font-extrabold mb-2" x-text="state.quitData.message || 'Jogo encerrado!'"></h2>
                    <p class="mb-1">🏆 Prêmio Atual: <b x-text="`R$ ${Number(state.quitData.current_prize ?? 0).toLocaleString('pt-BR')}`"></b></p>
                    <p class="mb-5">🛡️ Prêmio Garantido: <b x-text="`R$ ${Number(state.quitData.secured_prize ?? 0).toLocaleString('pt-BR')}`"></b></p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="/play" class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 font-semibold"
                           @mouseenter="getSfxComponent()?.pulse($el)" @click="getSfxComponent()?.playClick()">Iniciar Novo Jogo</a>
                        <a href="/" class="bg-white/80 text-black px-4 py-2 rounded-xl hover:bg-white font-semibold"
                           @mouseenter="getSfxComponent()?.pulse($el)" @click="getSfxComponent()?.playClick()">Página Inicial</a>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection
